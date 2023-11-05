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

    public function showEntries(array $navRoutes, string $colorTheme, string $userShortcut, string $sortingProperty, string $sort, string $date, int $perPage, int $currentPage) {
        $categories = $this->usersController->usersEntryCats();
        $entries = $this->entriesSortedByProperty($date, $perPage, $currentPage, $sortingProperty, $sort);
        $balance = $this->calculateMonthlyBalanceSheet($date);
        $sortButtons = $this->sortButtons($sort);
        $datePretty = (new DateTime($date))->format('F Y');
        $numPages = ceil($this->entryRepository->countEntriesOfMonth($date) / $perPage);
        $wdcategories = $this->wdController->wdCategoriesOfMonth($date);
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
            'date' => $date,
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
            $expCats = $this->usersController->usersExpCats();
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
            $expCats = $this->usersController->usersExpCats();
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

    public function calculateMonthlyBalanceSheet($date): ?array {
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

    public function entriesSortedByProperty(string $date, int $perPage, int $currentPage, string $sortingProperty, string $sort) {
        if(preg_match('/^.*Asc$/', $sort)) {
            $sortMode =  'Asc';
        } elseif (preg_match('/^.*Desc$/', $sort)) {
            $sortMode =  'Desc';
        }
        $sortedEntries = $this->entryRepository->fetchAllOfMonthPerPageSortedByProperty($date, $sortingProperty, strtoupper($sortMode), $perPage, $currentPage);
        if(empty($sortedEntries)) $sortedEntries = $this->entryRepository->fetchAllOfMonthPerPageSortedByProperty($date, $sortingProperty, strtoupper($sortMode), $perPage, 1);
        return $sortedEntries;
    }

    /* #TODO: For custom view ... list of entries with several custom settings
    public function entriesCustomSort() {
        // for category
        // for title
        // for income/ expense
        // only fixed
        // from ... to ...
        // from amount ... to ...
    }
    */

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
        $endDate = $year === date('Y') ? date('Y-m-d') : date($year . '-12-31');
        $entryCollectionraw = $this->entryRepository->fetchAllForTimeInterval($startDate, $endDate);
        $donationsEntries = [];
        foreach($entryCollectionraw AS $entry) {
            if(preg_match('/.*donation.*/i', $entry->category) | preg_match('/.*?donation.*?/i', $entry->title) | preg_match('/.*?donation.*?/i', $entry->comment)) {
                $donationsEntries[] = $entry;
            }
        }
        return $donationsEntries;
    }

    public function fetchAllForTimeInterval($startDate, $year) {
        $endDate = $year === date('Y') ? date('Y-m-d') : date($year . '-12-31');
        return $this->entryRepository->fetchAllForTimeInterval($startDate, $endDate);
    }

    public function dateFirstEntry() {
        $firstEntry = $this->entryRepository->fetchfirstEntry();
        return $firstEntry->dateslug;
    }

    public function timespanFirstEntry() {
        $firstEntry = $this->entryRepository->fetchfirstEntry();
        $timespanDays = round((strtotime(date('Y-m-d')) - strtotime($firstEntry->dateslug)) /(60*60*24), 0);
        return $timespanDays;
    }

    public function entryTrendByEntrytype($startDate, $year, $type) {
        $categories = $this->usersController->fetchUserCats()["{$type}cats"];
        $endDate = $year === date('Y') ? date('Y-m-d') : date($year . '-12-31');
        $months = @(int) round((strtotime($endDate) - strtotime($startDate))/ (60*60*24*30), 0)+1;
        $Array1 = [$categories[0]];
        $Array2 = [$categories[1]];
        $Array3 = [$categories[2]];
        $Array4 = [$categories[3]];
        $Array5 = [$categories[4]];
        $Array6 = [$categories[5]];
        $Array7 = [$categories[6]];
        $Array8 = [$categories[7]];
        $Array9 = [$categories[8]];
        $Array10 = [$categories[9]];
        $Array = [];
        for ($i = 0; $i < $months; $i++) {
            $sumCat1 = 0;
            $sumCat2 = 0;
            $sumCat3 = 0;
            $sumCat4 = 0;
            $sumCat5 = 0;
            $sumCat6 = 0;
            $sumCat7 = 0;
            $sumCat8 = 0;
            $sumCat9 = 0;
            $sumCat10 = 0;
            $month = date('Y-m', strtotime(" +{$i} months", strtotime($startDate)));
            $entriesOfMonth = $this->entryRepository->fetchAllOfGivenMonth($month);
            foreach ($entriesOfMonth as $entry) {
                if($entry->category === $categories[0]) if($type === "exp") $sumCat1 -= $entry->amount; elseif($type === "rev") $sumCat1 += $entry->amount;
                if($entry->category === $categories[1]) if($type === "exp") $sumCat2 -= $entry->amount; elseif($type === "rev") $sumCat2 += $entry->amount;
                if($entry->category === $categories[2]) if($type === "exp") $sumCat3 -= $entry->amount; elseif($type === "rev") $sumCat3 += $entry->amount;
                if($entry->category === $categories[3]) if($type === "exp") $sumCat4 -= $entry->amount; elseif($type === "rev") $sumCat4 += $entry->amount;
                if($entry->category === $categories[4]) if($type === "exp") $sumCat5 -= $entry->amount; elseif($type === "rev") $sumCat5 += $entry->amount;
                if($entry->category === $categories[5]) if($type === "exp") $sumCat6 -= $entry->amount; elseif($type === "rev") $sumCat6 += $entry->amount;
                if($entry->category === $categories[6]) if($type === "exp") $sumCat7 -= $entry->amount; elseif($type === "rev") $sumCat7 += $entry->amount;
                if($entry->category === $categories[7]) if($type === "exp") $sumCat8 -= $entry->amount; elseif($type === "rev") $sumCat8 += $entry->amount;
                if($entry->category === $categories[8]) if($type === "exp") $sumCat9 -= $entry->amount; elseif($type === "rev") $sumCat9 += $entry->amount;
                if($entry->category === $categories[9]) if($type === "exp") $sumCat10 -= $entry->amount; elseif($type === "rev") $sumCat10 += $entry->amount;
            }
            $Array1[] = $sumCat1;
            $Array2[] = $sumCat2;
            $Array3[] = $sumCat3;
            $Array4[] = $sumCat4;
            $Array5[] = $sumCat5;
            $Array6[] = $sumCat6;
            $Array7[] = $sumCat7;
            $Array8[] = $sumCat8;
            $Array9[] = $sumCat9;
            $Array10[] = $sumCat10;
        }
        if($Array1[0] !== '') $Array[] = $Array1;
        if($Array2[0] !== '') $Array[] = $Array2;
        if($Array3[0] !== '') $Array[] = $Array3;
        if($Array4[0] !== '') $Array[] = $Array4;
        if($Array5[0] !== '') $Array[] = $Array5;
        if($Array6[0] !== '') $Array[] = $Array6;
        if($Array7[0] !== '') $Array[] = $Array7;
        if($Array8[0] !== '') $Array[] = $Array8;
        if($Array9[0] !== '') $Array[] = $Array9;
        if($Array10[0] !== '') $Array[] = $Array10;    
        return($Array);
    }

    public function entryTrendByEntrytypeFixed($startDate, $year, $type) {
        $categories = $this->usersController->fetchUserCats()["{$type}cats"];
        $endDate = $year === date('Y') ? date('Y-m-d') : date($year . '-12-31');
        $months = @(int) round((strtotime($endDate) - strtotime($startDate))/ (60*60*24*30), 0)+1;
        $Array1 = [];
        $Array2 = [];
        $Array3 = [];
        $Array4 = [];
        $Array5 = [];
        $Array6 = [];
        $Array7 = [];
        $Array8 = [];
        $Array9 = [];
        $Array10 = [];
        $Array = [];
        $sumCat1 = 0;
        $sumCat1fixed = 0;
        $sumCat2 = 0;
        $sumCat2fixed = 0;
        $sumCat3 = 0;
        $sumCat3fixed = 0;
        $sumCat4 = 0;
        $sumCat4fixed = 0;
        $sumCat5 = 0;
        $sumCat5fixed = 0;
        $sumCat6 = 0;
        $sumCat6fixed = 0;
        $sumCat7 = 0;
        $sumCat7fixed = 0;
        $sumCat8 = 0;
        $sumCat8fixed = 0;
        $sumCat9 = 0;
        $sumCat9fixed = 0;
        $sumCat10 = 0;
        $sumCat10fixed = 0;
        for ($i = 0; $i < $months; $i++) {
            $month = date('Y-m', strtotime(" +{$i} months", strtotime($startDate)));
            $entriesOfMonth = $this->entryRepository->fetchAllOfGivenMonth($month);
            foreach ($entriesOfMonth as $entry) {
                if($entry->category === $categories[0] & $entry->fixedentry === 0) if($type === "exp") $sumCat1 -= $entry->amount;      elseif($type === "rev") $sumCat1 += $entry->amount;
                if($entry->category === $categories[0] & $entry->fixedentry === 1) if($type === "exp") $sumCat1fixed -= $entry->amount; elseif($type === "rev") $sumCat1fixed += $entry->amount;
                if($entry->category === $categories[1] & $entry->fixedentry === 0) if($type === "exp") $sumCat2 -= $entry->amount;      elseif($type === "rev") $sumCat2 += $entry->amount;
                if($entry->category === $categories[1] & $entry->fixedentry === 1) if($type === "exp") $sumCat2fixed -= $entry->amount; elseif($type === "rev") $sumCat2fixed += $entry->amount;
                if($entry->category === $categories[2] & $entry->fixedentry === 0) if($type === "exp") $sumCat3 -= $entry->amount;      elseif($type === "rev") $sumCat3 += $entry->amount;
                if($entry->category === $categories[2] & $entry->fixedentry === 1) if($type === "exp") $sumCat3fixed -= $entry->amount; elseif($type === "rev") $sumCat3fixed += $entry->amount;
                if($entry->category === $categories[3] & $entry->fixedentry === 0) if($type === "exp") $sumCat4 -= $entry->amount;      elseif($type === "rev") $sumCat4 += $entry->amount;
                if($entry->category === $categories[3] & $entry->fixedentry === 1) if($type === "exp") $sumCat4fixed -= $entry->amount; elseif($type === "rev") $sumCat4fixed += $entry->amount;
                if($entry->category === $categories[4] & $entry->fixedentry === 0) if($type === "exp") $sumCat5 -= $entry->amount;      elseif($type === "rev") $sumCat5 += $entry->amount;
                if($entry->category === $categories[4] & $entry->fixedentry === 1) if($type === "exp") $sumCat5fixed -= $entry->amount; elseif($type === "rev") $sumCat5fixed += $entry->amount;
                if($entry->category === $categories[5] & $entry->fixedentry === 0) if($type === "exp") $sumCat6 -= $entry->amount;      elseif($type === "rev") $sumCat6 += $entry->amount;
                if($entry->category === $categories[5] & $entry->fixedentry === 1) if($type === "exp") $sumCat6fixed -= $entry->amount; elseif($type === "rev") $sumCat6fixed += $entry->amount;
                if($entry->category === $categories[6] & $entry->fixedentry === 0) if($type === "exp") $sumCat7 -= $entry->amount;      elseif($type === "rev") $sumCat7 += $entry->amount;
                if($entry->category === $categories[6] & $entry->fixedentry === 1) if($type === "exp") $sumCat7fixed -= $entry->amount; elseif($type === "rev") $sumCat7fixed += $entry->amount;
                if($entry->category === $categories[7] & $entry->fixedentry === 0) if($type === "exp") $sumCat8 -= $entry->amount;      elseif($type === "rev") $sumCat8 += $entry->amount;
                if($entry->category === $categories[7] & $entry->fixedentry === 1) if($type === "exp") $sumCat8fixed -= $entry->amount; elseif($type === "rev") $sumCat8fixed += $entry->amount;
                if($entry->category === $categories[8] & $entry->fixedentry === 0) if($type === "exp") $sumCat9 -= $entry->amount;      elseif($type === "rev") $sumCat9 += $entry->amount;
                if($entry->category === $categories[8] & $entry->fixedentry === 1) if($type === "exp") $sumCat9fixed -= $entry->amount; elseif($type === "rev") $sumCat9fixed += $entry->amount;
                if($entry->category === $categories[9] & $entry->fixedentry === 0) if($type === "exp") $sumCat10 -= $entry->amount;     elseif($type === "rev") $sumCat10 += $entry->amount;
                if($entry->category === $categories[9] & $entry->fixedentry === 1) if($type === "exp") $sumCat10fixed -= $entry->amount; elseif($type === "rev") $sumCat10fixed += $entry->amount;
            }
            $Array1['regular'] = $sumCat1;
            $Array1['fixed'] = $sumCat1fixed;
            $Array2['regular'] = $sumCat2;
            $Array2['fixed'] = $sumCat2fixed;
            $Array3['regular'] = $sumCat3;
            $Array3['fixed'] = $sumCat3fixed;
            $Array4['regular'] = $sumCat4;
            $Array4['fixed'] = $sumCat4fixed;
            $Array5['regular'] = $sumCat5;
            $Array5['fixed'] = $sumCat5fixed;
            $Array6['regular'] = $sumCat6;
            $Array6['fixed'] = $sumCat6fixed;
            $Array7['regular'] = $sumCat7;
            $Array7['fixed'] = $sumCat7fixed;
            $Array8['regular'] = $sumCat8;
            $Array8['fixed'] = $sumCat8fixed;
            $Array9['regular'] = $sumCat9;
            $Array9['fixed'] = $sumCat9fixed;
            $Array10['regular'] = $sumCat10;
            $Array10['fixed'] = $sumCat10fixed;
        }
        if($categories[0] !== '') $Array[] = $Array1;
        if($categories[1] !== '') $Array[] = $Array2;
        if($categories[2] !== '') $Array[] = $Array3;
        if($categories[3] !== '') $Array[] = $Array4;
        if($categories[4] !== '') $Array[] = $Array5;
        if($categories[5] !== '') $Array[] = $Array6;
        if($categories[6] !== '') $Array[] = $Array7;
        if($categories[7] !== '') $Array[] = $Array8;
        if($categories[8] !== '') $Array[] = $Array9;
        if($categories[9] !== '') $Array[] = $Array10;    
        return($Array);
    }
}

