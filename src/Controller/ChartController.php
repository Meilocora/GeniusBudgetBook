<?php 

namespace App\Controller;

use App\Controller\WDController;
use App\Controller\YearlyController;
use App\Controller\EntryController;
use App\Entry\EntryRepository;
use App\Controller\ColorThemeController;

class ChartController extends AbstractController{

    public function __construct(
        protected WDController $wdController,
        protected YearlyController $yearlyController,
        protected EntryController $entryController,
        protected EntryRepository $entryRepository,
        protected ColorThemeController $colorThemeController) {}

    public function getStartDate($timeInterval, $year) {
        switch ($timeInterval) {
            case 'YTD':
                return $year . '-01-01';
            case 'YOY':
                return date('Y-m-d', strtotime(date($year . '-m-' . '01'))-(60*60*24*365));
            case 'ALL':
                return '1970-01-01';
            }
    }

    public function currentGoalShares($year, $queryDate, $dataset) {
        $currentGoalsArray = $this->yearlyController->fetchCurrentGoals($year);
        $totalGoalShares = [];
        switch ($dataset) {
            case 'wd':
                $currentTotalWealth = $this->wdController->currentTotalWealth($queryDate);
                if($currentTotalWealth < $currentGoalsArray['totalwealthgoal']) {
                    $totalWealthGoalShares['Current total wealth'] = $currentTotalWealth;
                    $totalWealthGoalShares['Missing wealth'] = $currentGoalsArray['totalwealthgoal'] - $currentTotalWealth;
                } else {
                    $totalWealthGoalShares['Current total wealth'] = $currentTotalWealth;
                    $totalWealthGoalShares['Missing wealth'] = 0;
                }
                $totalGoalShares = $totalWealthGoalShares;
                break;
            case 'liquid':
                $currentTotalLiquid = $this->wdController->currentTotalLiquid($queryDate);
                if($currentTotalLiquid < $currentGoalsArray['savinggoal']) {
                    $totalLiquidWealthGoalShares['Current total liquid wealth'] = $currentTotalLiquid;
                    $totalLiquidWealthGoalShares['Missing liquid wealth'] = $currentGoalsArray['savinggoal'] - $currentTotalLiquid;
                } else {
                    $totalLiquidWealthGoalShares['Current total liquid wealth'] = $currentTotalLiquid;
                    $totalLiquidWealthGoalShares['Missing liquid wealth'] = 0;
                }
                $totalGoalShares = $totalLiquidWealthGoalShares;
                break;
        }
        arsort($totalGoalShares); 
        return $totalGoalShares;
    }

    public function wdTrendArray($dataSet, $queryDate, $startDate) {
        $twoDWDArray = $this->wdController->wdTrend($queryDate, $startDate, $dataSet);
        if(!empty($twoDWDArray)) {
            $categoriesCount = sizeof($twoDWDArray[0])-1;
            $dateArray = [];
            $categoriesArray = array_slice(array_keys($twoDWDArray[0]), 1, sizeof(array_keys($twoDWDArray[0])) - 1);
            $wdTrendArray = [];
            
            for($y=0; $y<$categoriesCount; $y++) {
                $localArray = [];
                for($x=0; $x<sizeof($twoDWDArray); $x++) {
                    foreach($twoDWDArray[$x] AS $key => $value) {
                        if($key === $categoriesArray[$y]) $localArray[] = $value;
                    }
                }
                array_unshift($localArray, $categoriesArray[$y]);
                $wdTrendArray[] = $localArray;
            }
            for($x=0; $x<sizeof($twoDWDArray); $x++) {
                foreach($twoDWDArray[$x] AS $key => $value) {
                    if($key === 'dateslug') $dateArray[] = $value;
                }
            }
            $modifiedDateArray = [];
            foreach($dateArray AS $singleDate) {
                $modifiedDateArray[] = date_create($singleDate)->format('M Y');
            }
            $wdTrendArray[] = $modifiedDateArray;
            return $wdTrendArray;
        } else {
            return [[0], [0]];
        }
        
    }

    public function donationsValuesArray($year, $startDate) {
        $donationEntries = $this->entryController->donationsTrend($startDate, $year);
        $currentGoalsArray = $this->yearlyController->fetchCurrentGoals($year);
        $donationsValuesArray = [];
        $donationsActual = 0;
        foreach($donationEntries AS $entry) {
            $donationsActual += $entry->amount;
        }
        $donationsValuesArray[] = $donationsActual;
        $donationsValuesArray[] = max($currentGoalsArray['donationgoal'] - $donationsActual ,0);
        return $donationsValuesArray;
    }

    public function savingsArray($queryDate, $startDate) {
        $array = $this->wdController->wdTrend($queryDate, $startDate, 'actual-liquid');
        return $array;
    }

    public function budgetbookBalances($startDate, $endDate) {
        $entries = $this->entryController->fetchAllForTimeInterval($startDate, $endDate);
        $budgetbookBalancesArray = [];
        $revenues = 0;
        $expenditures = 0;
        foreach($entries AS $entry) {
            if($entry->income === 1) $revenues += $entry->amount;
            if($entry->income === 0) $expenditures -= $entry->amount;
        }
        $budgetbookBalancesArray['totalCashflow'] = $revenues + $expenditures;
        $budgetbookBalancesArray['revenues'] = $revenues;
        $budgetbookBalancesArray['expenditures'] = $expenditures;
        return $budgetbookBalancesArray;
    }

    public function fixedBalances($startDate, $endDate) {
        $entries = $this->entryController->fetchAllForTimeInterval($startDate, $endDate);
        $budgetbookBalancesArray = [];
        $revenues = 0;
        $expenditures = 0;
        foreach($entries AS $entry) {
            if($entry->income === 1 && $entry->fixedentry === 1) $revenues += $entry->amount;
            if($entry->income === 0 && $entry->fixedentry === 1) $expenditures -= $entry->amount;
        }
        $budgetbookBalancesArray['totalCashflow'] = $revenues + $expenditures;
        $budgetbookBalancesArray['revenues'] = $revenues;
        $budgetbookBalancesArray['expenditures'] = $expenditures;
        return $budgetbookBalancesArray;
    }

    public function alltimeBalances() {
        $startDate = '01-01-1970';
        $endDate = date('Y-m-d');
        return $this->budgetbookBalances($startDate, $endDate);
    }


}