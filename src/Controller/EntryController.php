<?php 

namespace App\Controller;

use App\Entry\EntryModel;
use App\Entry\EntryRepository;
use App\FrontendWizard\MonthlyPageWizard;
use App\Controller\DBController;
use App\Controller\WDController;
use App\Controller\UsersController;
use DateTime;

class EntryController extends AbstractController{

    public function __construct(
        protected EntryModel $entryModel,
        protected EntryRepository $entryRepository,
        protected MonthlyPageWizard $monthlyPageWizard,
        protected DBController $dbController,
        protected WDController $wdController,
        protected UsersController $usersController) {}

    public function showEntries(array $navRoutes, string $colorTheme, string $userShortcut, string $sortingProperty, string $sort, string $date, int $perPage, int $currentPage, string $username) {
        $categories = $this->usersController->usersEntryCats($username);
        $unsortedEntries = $this->entryRepository->fetchAllOfMonthPerPage($date, $perPage, $currentPage);
        $entries = $this->entryRepository->sortByProperty($unsortedEntries, $sortingProperty, $sort);
        $balance = $this->calculateMonthlyBalanceSheet($date);
        $sortButtons = $this->sortButtons($sort);
        $datePretty = (new DateTime($date))->format('F Y');
        $numPages = ceil($this->entryRepository->countEntriesOfMonth($date) / $perPage);
        $wdcategories = $this->wdController->wdCategoriesOfMonth($username, $date);
        if(empty($this->checkUntransferedFixedEntries($date))) {
            $transferPossible = false;
        } else {
            $transferPossible = true;
        }
        $this->render('budget-book/monthly-page', [
            'categories' => $categories,
            'entries' => $entries,
            'navRoutes' => $navRoutes,
            'colorTheme' => $colorTheme,
            'userShortcut' => $userShortcut,
            'balance' => $balance,
            'sortButtons' => $sortButtons,
            'datePretty' => $datePretty,
            'numPages' => $numPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'wdcategories' => $wdcategories,
            'transferPossible' => $transferPossible
        ]);
    }

    public function create() {
        if(!empty($_POST)) {
            $category = @(string) ($_POST['category'] ?? '');
            $title = @(string) ($_POST['title'] ?? '');
            $amount = @(float) ($_POST['amount'] ?? '');
            $date = @(string) ($_POST['date'] ?? '');
            $comment = @(string) ($_POST['comment'] ?? '');
            $fixedentry = @(float) ($_POST['fixedentry'] ?? '0');
            $expCats = $this->usersController->usersExpCats($_SESSION['username']);
            if(in_array($category, $expCats)) {
                $income = 0;
            } else {
                $income = 1;
            }
            if(!empty($category) && !empty($title) && !empty($title) && !empty($amount) && !empty($date)) {
                $success = $this->entryRepository->create($category, $title, $amount, $date, $comment, $income, $fixedentry);
                
                if($success) {
                    header("Location: ./?route=monthly-page#entry-form");
                    return;
                }
                else {
                    $errorMessage[] = "Der Eintrag konnte nicht hinzugefügt werden!";
                }
            }
            else {
                $errorMessage[] = "Das Formular wurde nicht vollständig ausgeführt!";
            }
            $this->render('pages/budget-book/monthly-page#entry-form', [
                'errorMessage' => $errorMessage
            ]);
        }
        $this->render('pages/budget-book/monthly-page#entry-form', [
            'errorMessage' => $errorMessage
        ]);

    }

    public function delete($id) {
        $this->entryRepository->delete($id);
        header("Location: ./?route=monthly-page#monthly-table");
    }

    public function update($id) {
        if(!empty($_POST)) {
            $category = @(string) ($_POST['category'] ?? '');
            $title = @(string) ($_POST['title'] ?? '');
            $amount = @(float) ($_POST['amount'] ?? '');
            $dateslug = @(string) ($_POST['dateslug'] ?? '');
            $comment = @(string) ($_POST['comment'] ?? '');
            $fixedentry = @(float) ($_POST['fixedentry'] ?? '0');
            $expCats = $this->usersController->usersExpCats($_SESSION['username']);
            if(in_array($category, $expCats)) {
                $income = 0;
            } else {
                $income = 1;
            }
            if(!empty($category) && !empty($title) && !empty($amount) && !empty($dateslug)) {
                $this->entryRepository->update($id, $category, $title, $amount, $dateslug, $comment, $income, $fixedentry);
                header("Location: ./?route=monthly-page#monthly-table");
                return;
            }
        
        }
    }

    private function calculateMonthlyBalanceSheet($date): ?array {
        $allEntries = $this->entryRepository->fetchAllOfMonth($date);
        $fixedIncome = 0;
        $income = 0;
        $fixedExpenses = 0;
        $expenses = 0;
        foreach($allEntries AS $entry) {
            
            if($entry->income === 1) {
                if($entry->fixedentry === 1) {
                    $fixedIncome += $entry->amount;
                }
                $income += $entry->amount;
            }
            else {
                if($entry->fixedentry === 1) {
                    $fixedExpenses -= $entry->amount;
                }
                $expenses -= $entry->amount;
            }

        }
        $fixedBalance = $fixedIncome + $fixedExpenses;
        $balance = $income + $expenses;
        return ['fixedIncome' => $fixedIncome, 'income' => $income, 'fixedExpenses' => $fixedExpenses, 'expenses' => $expenses, 'fixedBalance' => $fixedBalance, 'balance' =>$balance];
    }

    public function sortButtons($sort) {
        $sortButtonArray = [];

        switch ($sort) {
            case 'sortCategoryAsc':
                $sortButtonArray[0] = $this->monthlyPageWizard->sortButtonCategoryAsc();
                $sortButtonArray[1] = $this->monthlyPageWizard->sortButtonTitleNoSort();
                $sortButtonArray[2] = $this->monthlyPageWizard->sortButtonAmountNoSort();
                $sortButtonArray[3] = $this->monthlyPageWizard->sortButtonDateNoSort();
                return $sortButtonArray;
            case 'sortCategoryDesc':
                $sortButtonArray[0] = $this->monthlyPageWizard->sortButtonCategoryDesc();
                $sortButtonArray[1] = $this->monthlyPageWizard->sortButtonTitleNoSort();
                $sortButtonArray[2] = $this->monthlyPageWizard->sortButtonAmountNoSort();
                $sortButtonArray[3] = $this->monthlyPageWizard->sortButtonDateNoSort();
                return $sortButtonArray;
            case 'sortTitleAsc':
                $sortButtonArray[0] = $this->monthlyPageWizard->sortButtonCategoryNoSort();
                $sortButtonArray[1] = $this->monthlyPageWizard->sortButtonTitleAsc();
                $sortButtonArray[2] = $this->monthlyPageWizard->sortButtonAmountNoSort();
                $sortButtonArray[3] = $this->monthlyPageWizard->sortButtonDateNoSort();
                return $sortButtonArray;
            case 'sortTitleDesc':
                $sortButtonArray[0] = $this->monthlyPageWizard->sortButtonCategoryNoSort();
                $sortButtonArray[1] = $this->monthlyPageWizard->sortButtonTitleDesc();
                $sortButtonArray[2] = $this->monthlyPageWizard->sortButtonAmountNoSort();
                $sortButtonArray[3] = $this->monthlyPageWizard->sortButtonDateNoSort();
                return $sortButtonArray;
            case 'sortAmountAsc':
                $sortButtonArray[0] = $this->monthlyPageWizard->sortButtonCategoryNoSort();
                $sortButtonArray[1] = $this->monthlyPageWizard->sortButtonTitleNoSort();
                $sortButtonArray[2] = $this->monthlyPageWizard->sortButtonAmountAsc();
                $sortButtonArray[3] = $this->monthlyPageWizard->sortButtonDateNoSort();
                return $sortButtonArray;
            case 'sortAmountDesc':
                $sortButtonArray[0] = $this->monthlyPageWizard->sortButtonCategoryNoSort();
                $sortButtonArray[1] = $this->monthlyPageWizard->sortButtonTitleNoSort();
                $sortButtonArray[2] = $this->monthlyPageWizard->sortButtonAmountDesc();
                $sortButtonArray[3] = $this->monthlyPageWizard->sortButtonDateNoSort();
                return $sortButtonArray;
            case 'sortDateAsc':
                $sortButtonArray[0] = $this->monthlyPageWizard->sortButtonCategoryNoSort();
                $sortButtonArray[1] = $this->monthlyPageWizard->sortButtonTitleNoSort();
                $sortButtonArray[2] = $this->monthlyPageWizard->sortButtonAmountNoSort();
                $sortButtonArray[3] = $this->monthlyPageWizard->sortButtonDateAsc();
                return $sortButtonArray;
            case 'sortDateDesc':
                $sortButtonArray[0] = $this->monthlyPageWizard->sortButtonCategoryNoSort();
                $sortButtonArray[1] = $this->monthlyPageWizard->sortButtonTitleNoSort();
                $sortButtonArray[2] = $this->monthlyPageWizard->sortButtonAmountNoSort();
                $sortButtonArray[3] = $this->monthlyPageWizard->sortButtonDateDesc();
                return $sortButtonArray;
            }
    }

    public function transferFixedEntries($date) {
        $date = $date . '-01';
        $lastMonth = date("Y-m-d",strtotime("-1 month", strtotime($date . '-01')));
        $entriesLastMonth = $this->entryRepository->fetchAllOfGivenMonth($lastMonth);
        $untransferedFixedEntries = $this->checkUntransferedFixedEntries($date);
        foreach($entriesLastMonth AS $entryLastMonth) {
            foreach($untransferedFixedEntries AS $untransferedFixedEntry) {
                if( $entryLastMonth->fixedentry === 1 && 
                    $entryLastMonth->category === $untransferedFixedEntry[0] && 
                    $entryLastMonth->title === $untransferedFixedEntry[1] &&
                    $entryLastMonth->amount === $untransferedFixedEntry[2] &&
                    $entryLastMonth->income === $untransferedFixedEntry[3]) $this->entryRepository->create($entryLastMonth->category, $entryLastMonth->title, $entryLastMonth->amount, $date, $entryLastMonth->comment, $entryLastMonth->income, $entryLastMonth->fixedentry);
            }
        }
        header("Location: ./?route=monthly-page#monthly-table");
        return;
    }

    public function checkUntransferedFixedEntries($date) {
        $lastMonth = date("Y-m-d",strtotime("-1 month", strtotime($date)));
        $entriesLastMonth = $this->entryRepository->fetchAllOfGivenMonth($lastMonth);
        $entriesThisMonth = $this->entryRepository->fetchAllOfGivenMonth($date);
        $compareArrayLastMonth = [];
        $compareArrayThisMonth = [];
        foreach($entriesLastMonth AS $entry) {
            if($entry->fixedentry === 1) {
                $compareArrayLastMonth[] = [
                    $entry->category,
                    $entry->title,
                    $entry->amount,
                    $entry->income,
                    $entry->fixedentry
                ];
            }
            
        }
        foreach($entriesThisMonth AS $entry) {
            if($entry->fixedentry === 1) {
                $compareArrayThisMonth[] = [
                    $entry->category,
                    $entry->title,
                    $entry->amount,
                    $entry->income,
                    $entry->fixedentry
                ];
            }
        }
        $untransferedFixedEntries = [];
        foreach($compareArrayLastMonth AS $entryLastMonth) {
            if(!in_array($entryLastMonth, $compareArrayThisMonth)) $untransferedFixedEntries[] = $entryLastMonth;
        }
        
        return $untransferedFixedEntries;
    }

    public function donationsTrend($startDate, $year) {
        $username = strtolower($_SESSION['username']);
        $endDate = $year === date('Y') ? date('Y-m-d') : date($year . '-12-31');
        $entryCollectionraw = $this->entryRepository->fectAllForTimeInterval($username, $startDate, $endDate);
        $donationsEntries = [];
        foreach($entryCollectionraw AS $entry) {
            if(preg_match('/.*donation.*/i', $entry->category) | preg_match('/.*?donation.*?/i', $entry->title) | preg_match('/.*?donation.*?/i', $entry->comment)) {
                $donationsEntries[] = $entry;
            }
        }
        return $donationsEntries;
    }

}

