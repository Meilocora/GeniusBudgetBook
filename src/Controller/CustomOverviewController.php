<?php 

namespace App\Controller;

use App\Controller\EntryController;
use App\Entry\EntryRepository;
use App\Controller\UsersController;
use App\Controller\ChartController;
use App\Controller\ColorThemeController;

class CustomOverviewController extends AbstractController{

    public function __construct(
        protected EntryController $entryController,
        protected EntryRepository $entryRepository,
        protected UsersController $usersController,
        protected ChartController $chartController,
        protected ColorThemeController $colorThemeController) {}

    public function showCustomOverview($navRoutes, $colorTheme, $userShortcut, $chartColorSet, $cTimeinterval, $cStartDate, $cEndDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cTitles, $cTitleQuery, $cAmounts, $fromAmount, $toAmount, $cComments, $cCommentQuery, $cSortingProperty, $cSort, $currentPage, $perPage, $cChartSearch, $cChartSearchCategory, $cChartSearchRegex, $cChartStartDate, $cChartEndDate) {
        $startDate = $this->giveInterval($cTimeinterval, $cStartDate, $cEndDate)[0];
        $endDate = $this->giveInterval($cTimeinterval, $cStartDate, $cEndDate)[1];
        $timespan = max(1, calculateTimespanDays($startDate, $endDate));
        $timespanAccount = max(1, $this->entryController->timespanFirstEntry());
        $categories = $this->giveCategories();
        $maxAmount = $this->entryController->highestAmount();

        $paginationEntries = $this->givePaginationEntries($startDate, $endDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort);
        $sortButtons = $this->entryController->sortButtons($cSort, 'custom-overview');
        $countEntries = $this->countEntries($startDate, $endDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort);
        $numPages = (int) ceil($countEntries / $perPage);

        $entries = $this->giveEntries($startDate, $endDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort);
        $timespanArray = [$timespan, round($timespan/7, 1), round($timespan/30.43, 1), round($timespan/365, 1)];
        $balances = $this->giveBalances($entries);
        $fixedBalances = $this->giveFixedBalances($entries);
        $alltimeBalances = $this->giveAlltimeBalances($cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort); 
        $fixedAlltimeBalances = $this->giveFixedAlltimeBalances($cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort);
        $averagePercentages = $this->giveAveragePercentages($timespan, $timespanAccount, array_values($balances), array_values($fixedBalances), array_values($alltimeBalances), array_values($fixedAlltimeBalances));

        $lineColors = ["rgb(20,113,73,0.5)", "rgb(200,20,20,0.5)"];
        $chartEntries = $this->giveChartEntries($cChartSearch, $cChartSearchCategory, $cChartSearchRegex, $cChartStartDate, $cChartEndDate);
        if(!empty($chartEntries)) {
            $dateArray = $this->chartController->dateArray($cChartStartDate, $cChartEndDate);
            $entryTrend = $this->chartController->entriesTrend($cChartSearch, $dateArray, $chartEntries);
        } else {
            $dateArray = [null];
            $entryTrend = [null];
        }
        $chartTimespan = calculateTimespanDays($cChartStartDate, $cChartEndDate);
        $chartSum = $this->giveChartSum($chartEntries);
        $numChartEntries = $this->countChartEntries($chartEntries);
        if($chartSum !== 0) {
            $chartEntryAverage = $chartSum / $numChartEntries;
        } else {
            $chartEntryAverage = 0;
        }
        
        $chartEntryIncrease = $this->entryIncrease($chartEntries);

        $this->render('budget-book/custom-overview', [
            'navRoutes' => $navRoutes,
            'colorTheme' => $colorTheme,
            'userShortcut' => $userShortcut,
            'cTimeinterval' => $cTimeinterval,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'timespan' => $timespan,
            'timespanAccount' => $timespanAccount,
            'cEntryType' => $cEntryType,
            'cFixation' => $cFixation,
            'cCategories' => $cCategories,
            'cCategoryQuery' => $cCategoryQuery,
            'categories' => $categories,
            'cTitles' => $cTitles,
            'cTitleQuery' => $cTitleQuery,
            'cAmounts' => $cAmounts,
            'maxAmount' => $maxAmount,
            'fromAmount' => $fromAmount,
            'toAmount' => $toAmount,
            'cComments' => $cComments,
            'cCommentQuery' => $cCommentQuery,
            'sortButtons' =>$sortButtons,
            'paginationEntries' => $paginationEntries,
            'numPages' => $numPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'countEntries' => $countEntries,
            'timespanArray' => $timespanArray,
            'balances' => $balances,
            'fixedBalances' => $fixedBalances,
            'alltimeBalances' => $alltimeBalances,
            'fixedAlltimeBalances' => $fixedAlltimeBalances,
            'averagePercentages' => $averagePercentages,
            'cChartSearch' => $cChartSearch,
            'cChartSearchCategory' => $cChartSearchCategory,
            'cChartSearchRegex' => $cChartSearchRegex,
            'cChartStartDate' => $cChartStartDate, 
            'cChartEndDate' => $cChartEndDate,
            'lineColors' => $lineColors,
            'dateArray' => $dateArray,
            'entryTrend' => $entryTrend,
            'chartTimespan' => $chartTimespan,
            'chartSum' => $chartSum,
            'numChartEntries' => $numChartEntries,
            'chartEntryAverage' => $chartEntryAverage,
            'chartEntryIncrease' => $chartEntryIncrease,
        ]);
    }

    public function giveInterval($cTimeinterval, $startDate, $endDate) {
        switch ($cTimeinterval) {
            case 'YTD':
                $startDate = date('Y') . '-01-01';
                $endDate = date('Y-m-d');
                break;
            case 'YoY':
                $startDate = date('Y')-1 . '-' . date('m-d');
                $endDate = date('Y-m-d');
                break;
            case 'All':
                $startDate = $this->entryController->dateFirstEntry();
                $endDate = date('Y-m-d');
            case 'Custom':
                break;
        }
        return [$startDate, $endDate];
    }

    public function giveCategories() {
        $rawCategories = $this->usersController->fetchUserCats();
        $categories = [];
        foreach ($rawCategories['revcats'] as $category) {
            if($category !== '') $categories[] = $category;
        }
        foreach ($rawCategories['expcats'] as $category) {
            if($category !== '') $categories[] = $category;
        }
        return $categories;
    }

    public function givePaginationEntries($startDate, $endDate, $entryType, $fixation, $entryCategory, $categoryQuery, $amounts, $fromAmount, $toAmount, $titles, $titleQuery, $comments, $commentQuery, $perPage, $currentPage, $sortingProperty, $sort) {
        $queryArray = $this->generateQueryArray($startDate, $endDate, $entryType, $fixation, $entryCategory, $categoryQuery, $amounts, $fromAmount, $toAmount, $titles, $titleQuery, $comments, $commentQuery, $perPage, $currentPage, $sortingProperty, $sort);
        $queryString = implode('', $queryArray);
        return $this->entryRepository->fetchCustomQuery($queryString);
    }

    public function giveEntries($startDate, $endDate, $entryType, $fixation, $entryCategory, $categoryQuery, $amounts, $fromAmount, $toAmount, $titles, $titleQuery, $comments, $commentQuery, $perPage, $currentPage, $sortingProperty, $sort) {
        $queryArray = $this->generateQueryArray($startDate, $endDate, $entryType, $fixation, $entryCategory, $categoryQuery, $amounts, $fromAmount, $toAmount, $titles, $titleQuery, $comments, $commentQuery, $perPage, $currentPage, $sortingProperty, $sort);
        array_pop($queryArray);
        $queryString = implode('', $queryArray);
        return $this->entryRepository->fetchCustomQuery($queryString);
    }

    public function generateQueryArray($startDate, $endDate, $entryType, $fixation, $entryCategory, $categoryQuery, $amounts, $fromAmount, $toAmount, $titles, $titleQuery, $comments, $commentQuery, $perPage, $currentPage, $sortingProperty, $sort) {
        $queryArray = [];
        $queryArray[] = "WHERE `dateslug` BETWEEN '{$startDate}' AND '{$endDate}'";
        switch ($entryType) {
            case 'All':
                break;
            case 'revenues':
                $queryArray[] = " AND `income` = 1 ";
                break;
            case 'expenditures':
                $queryArray[] = " AND `income` = 0 ";
                break;
        }
        switch ($fixation) {
            case 'AllFixations':
                break;
            case 'fixed':
                $queryArray[] = " AND `fixedentry` = 1 ";
                break;
            case 'unfixed':
                $queryArray[] = " AND `fixedentry` = 0 ";
                break;
        }
        switch ($entryCategory) {
            case 'allCategories':
                break;
            case 'certainCategory':
                $queryArray[] = " AND `category` = '{$categoryQuery}' ";
                break;
        }
        switch ($amounts) {
            case 'allAmounts':
                break;
            case 'Custom':
                $queryArray[] = " AND `amount` BETWEEN {$fromAmount} AND {$toAmount} ";
                break;
        }
        switch ($titles) {
            case 'certainTitle':
                $queryArray[] = " AND `title` REGEXP '.*{$titleQuery}.*'";
        }
        switch ($comments) {
            case 'noComments': 
                $queryArray[] = " AND `comment` = ''";
            case 'certainComment': 
                $queryArray[] = " AND `comment` REGEXP '.*{$commentQuery}.*'";
        }
        if(preg_match('/^.*Asc$/', $sort)) {
            $sortMode =  'Asc';
        } elseif (preg_match('/^.*Desc$/', $sort)) {
            $sortMode =  'Desc';
        }
        $limit = $perPage;
        $offset = ($currentPage - 1) * $perPage;
        $queryArray[] = " ORDER BY `{$sortingProperty}` {$sortMode} LIMIT {$limit} OFFSET {$offset}";
        return $queryArray;
    }

    public function countEntries($startDate, $endDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort) {
        $queryArray = $this->generateQueryArray($startDate, $endDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort);
        array_pop($queryArray);
        $queryString = implode("", $queryArray);
        return $this->entryRepository->countCustomEntries($queryString);
    }

    public function filterEntries($rawEntries, $filterCriteria, $regex) {
        $entries = [];
        switch ($filterCriteria) {
            case 'title':
                foreach ($rawEntries as $entry) {
                    if(preg_match("/.*{$regex}.*/", $entry->title)) $entries[] = $entry;
                }   
                break;
            case 'noComment':
                foreach ($rawEntries as $entry) {
                    if($entry->comment === $regex) $entries[] = $entry;
                }
                break;
            case 'comment':
                foreach ($rawEntries as $entry) {
                    if(preg_match("/.*{$regex}.*/", $entry->comment)) $entries[] = $entry;
                }
                break;
            default:
                $entries = $rawEntries;
        }
        return $entries;
    }

    public function giveBalances($entries) {
        $balancesArray = [];
        $revenues = 0;
        $expenditures = 0;
        foreach($entries AS $entry) {
            if($entry->income === 1) $revenues += $entry->amount;
            if($entry->income === 0) $expenditures -= $entry->amount;
        }
        $balancesArray['totalCashflow'] = $revenues + $expenditures !== 0 ? $revenues + $expenditures : .001;;
        $balancesArray['revenues'] = $revenues !== 0 ? $revenues + $expenditures : .001;;
        $balancesArray['expenditures'] = $expenditures !== 0 ? $expenditures : .001;;
        return $balancesArray;
    }

    public function giveFixedBalances($entries) {
        $balancesArray = [];
        $revenues = 0;
        $expenditures = 0;
        foreach($entries AS $entry) {
            if($entry->income === 1 && $entry->fixedentry === 1) $revenues += $entry->amount;
            if($entry->income === 0 && $entry->fixedentry === 1) $expenditures -= $entry->amount;
        }
        $balancesArray['totalCashflow'] = $revenues + $expenditures !== 0 ? $revenues + $expenditures : .001;
        $balancesArray['revenues'] = $revenues !== 0 ? $revenues + $expenditures : .001;
        $balancesArray['expenditures'] = $expenditures !== 0 ? $expenditures : .001;
        return $balancesArray;
    }

    public function giveAlltimeBalances($cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort) {
        $startDate = $this->entryController->dateFirstEntry();
        $endDate = date('Y-m-d');
        $entries = $this->giveEntries($startDate, $endDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort);
        return $this->giveBalances($entries);
    }

    public function giveFixedAlltimeBalances($cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort) {
        $startDate = $this->entryController->dateFirstEntry();
        $endDate = date('Y-m-d');
        $entries = $this->giveEntries($startDate, $endDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort);
        return $this->giveFixedBalances($entries);
    }

    public function giveAveragePercentages($timespan, $timespanAccount, $balances, $fixedBalances, $alltimeBalances, $fixedAlltimeBalances) {
        $averagePercentages = [];
        $averagePercentages['cashflow'] = $this->compareWithAverage($timespan, $timespanAccount, $balances[0], $alltimeBalances[0]);
        $averagePercentages['fixedCashflow'] = $this->compareWithAverage($timespan, $timespanAccount, $fixedBalances[0], $fixedAlltimeBalances[0]);
        $averagePercentages['revenues'] = $this->compareWithAverage($timespan, $timespanAccount, $balances[1], $alltimeBalances[1]);
        $averagePercentages['fixedRevenues'] = $this->compareWithAverage($timespan, $timespanAccount, $fixedBalances[1], $fixedAlltimeBalances[1]);
        $averagePercentages['expenditures'] = $this->compareWithAverage($timespan, $timespanAccount, $balances[2], $alltimeBalances[2]);
        $averagePercentages['fixedExpenditures'] = $this->compareWithAverage($timespan, $timespanAccount, $fixedBalances[2], $fixedAlltimeBalances[2]);
        return $averagePercentages;
    }

    public function compareWithAverage($timespan, $timespanAccount, $value, $compareValue) {
        $averageValue = $compareValue/$timespanAccount*$timespan;
        if($value > $averageValue & $value < 0 & $averageValue < 0) {
            return ($value/$averageValue-1)*-100;
        } elseif ($value > $averageValue & $value > 0 & $averageValue < 0) {
            return ($value/$averageValue-1)*-100;
        } elseif ($value < $averageValue & $value < 0 & $averageValue < 0) {
            return ($value/$averageValue-1)*-100;
        } else {
            return ($value/$averageValue-1)*100;
        }
    }

    public function giveChartEntries($cChartSearch, $cChartSearchCategory, $cChartSearchRegex, $cChartStartDate, $cChartEndDate) {
        $queryArray = [];
        $startDate = $cChartStartDate . '-01';
        $endDate = date('Y-m-t', strtotime($cChartEndDate));
        $queryArray[] = "WHERE `dateslug` BETWEEN '{$startDate}' AND '{$endDate}'";
        switch ($cChartSearch) {
            case 'category':
                $queryArray[] = " AND `category` = '{$cChartSearchCategory}' ";
                break;
            case 'title':
                $queryArray[] = " AND `title` REGEXP '.*{$cChartSearchRegex}.*'";        
                break;
        }
        $queryString = implode('', $queryArray);
        return $this->entryRepository->fetchCustomQuery($queryString);
    }

    public function giveChartSum($entries) {
        $sum = 0;
        foreach ($entries as $entry) {
            if(gettype($entry) !== 'string') {
                if($entry->income === 1) $sum += $entry->amount; else $sum -= $entry->amount;
            }
        }
        return $sum;
    }

    public function countChartEntries($entries) {
        $count = 0;
        foreach ($entries as $entry) {
            if(gettype($entry) !== 'string') $count ++;
        }
        return $count;
    }

    public function entryIncrease($entries) {
        if(!empty($entries)) {
            $fstEntry = $entries[0];
            $lastEntry = $entries[sizeof($entries)-1];
        } else {
            $fstEntry = [];
            $lastEntry = [];
        }
       
        if(!empty($lastEntry) & !empty($fstEntry)) {
            return (($lastEntry->amount/$fstEntry->amount)-1)*100;
        } else {
            return 0;
        }
    }
}