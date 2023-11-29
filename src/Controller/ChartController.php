<?php 

namespace App\Controller;

use App\Controller\WDController;
use App\Controller\YearlyController;
use App\Controller\EntryController;
use App\Entry\EntryRepository;
use App\Controller\ColorThemeController;
use App\Controller\UsersController;

class ChartController extends AbstractController{

    public function __construct(
        protected WDController $wdController,
        protected YearlyController $yearlyController,
        protected EntryController $entryController,
        protected EntryRepository $entryRepository,
        protected ColorThemeController $colorThemeController,
        protected UsersController $usersController) {}

    public function getStartDate($timeInterval, $year) {
        switch ($timeInterval) {
            case 'YTD':
                return $year . '-01-01';
            case 'YOY':
                return date('Y-m-d', strtotime(date($year . '-m-' . '01'))-(60*60*24*365));
            case 'ALL':
                return $this->wdController->firstWDBalanceDate();
            case 'Custom':
                return false;
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
        // arsort($totalGoalShares); 
        return $totalGoalShares;
    }

    public function wdTrendArray($dataSet, $queryDate, $startDate) {
        // $twoDWDArray = array_reverse($this->wdController->wdTrend($queryDate, $startDate, $dataSet));
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

    public function donationsValuesArray($year, $startDate, $queryDate) {
        $donationEntries = $this->entryController->donationsTrend($startDate, $queryDate);
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
        if($budgetbookBalancesArray['revenues'] === 0 & $budgetbookBalancesArray['expenditures'] === 0) return ['totalCashflow' => 1, 'revenues' => 1, 'expenditures' => 1];
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
        if($budgetbookBalancesArray['revenues'] === 0 & $budgetbookBalancesArray['expenditures'] === 0) return ['totalCashflow' => 1, 'revenues' => 1, 'expenditures' => 1];
        return $budgetbookBalancesArray;
    }

    public function alltimeBalances() {
        $startDate = $this->entryController->dateFirstEntry();
        $endDate = date('Y-m-d');
        return $this->budgetbookBalances($startDate, $endDate);
    }

    public function cashflowOverTimeinterval($startDate, $endDate) {
        $months = @(int) round((strtotime($endDate) - strtotime($startDate))/ (60*60*24*30), 0)+1;
        $balanceArray = ['Cashflow'];
        $revenuesArray = ['Revenues'];
        $expendituresArray = ['Expenditures'];
        $cashflowArray = [];
        for ($i = 0; $i < $months; $i++) {
            $month = date('Y-m', strtotime(" +{$i} months", strtotime($startDate)));
            $balance = $this->entryController->calculateMonthlyBalanceSheet($month);
            $balanceArray[] = $balance['balance'];
            $revenuesArray[] = $balance['income'];
            $expendituresArray[] = $balance['expenses'];
        }
        $cashflowArray['balances'] = $balanceArray;
        $cashflowArray['revenues'] = $revenuesArray;
        $cashflowArray['expenditures'] = $expendituresArray;
        return $cashflowArray;
    }

    public function dateArray($startDate, $endDate) {
        $months = @(int) round((strtotime($endDate) - strtotime($startDate))/ (60*60*24*30), 0);
        $dateArray = [];
        for ($i = 0; $i < $months; $i++) {
            $month = date('Y-m', strtotime(" +{$i} months", strtotime($startDate)));
            $dateArray [] = date('Y M', strtotime($month));
        }
        if(!in_array(date(('Y M'), strtotime($endDate)), $dateArray)) $dateArray [] = date(('Y M'), strtotime($endDate));
        return $dateArray;
    }

    public function yearsArray($years) {
        $yearsArray = [];
        for($i=(int)date('Y'); $i<$years+date('Y'); $i++) {
            $yearsArray[] = $i;
        }
        return $yearsArray;
    }

    public function entryDataByTypeC($startDate, $year, $type) {
        $entryArray = $this->entryController->entryTrendByEntrytype($startDate, $year, $type);
        for ($i = 0; $i < sizeof($entryArray); $i++) {;
            if($entryArray[$i][0] === '') unset($entryArray[$i]);
        }
        return $entryArray;
    }

    public function summedEntryDataC($dataArray) {
        $summedEntryData = [];
        for ($i=0; $i<sizeof($dataArray) ; $i++) {
            $localSum = 0;
            for ($x=1; $x<sizeof($dataArray[$i]) ; $x++) { 
                $localSum += $dataArray[$i][$x];
            }
            $summedEntryData[$dataArray[$i][0]] = $dataArray[$i][0];
            $summedEntryData[$dataArray[$i][0]] = $localSum;
        }
       return $summedEntryData;
    }

    public function summedEntryDataCAveragePerMonth($endDate, $type) {
        $startDate = $this->entryController->dateFirstEntry();
        $trendData = $this->entryDataByTypeC($startDate, $endDate, $type);
        $summedData = $this->summedEntryDataC($trendData);
        $timespanDays = max(1, calculateTimespanDays($startDate, $endDate));
        $resultArray = [];
        foreach ($summedData AS $key => $value) {
            if($value !== 0) $resultArray[$key] = round($value/$timespanDays*30, 0);
        }
        return $resultArray;
    }

    public function entriesTrend($search, $dateArray, $entries) {
        $entriesTrend = [];
        switch ($search) {
            case 'category':
                $entriesTrend = [$entries[0]->category];
                break;
            case 'title':
                $entriesTrend = [$entries[0]->title];
                break;
        }
        foreach ($dateArray AS $date) {
            $localSum = 0;
            foreach ($entries AS $entry) {
                if (date('Y-m', strtotime($date)) === date('Y-m', strtotime($entry->dateslug))) {
                    if($entry->income === 1)$localSum += $entry->amount; else $localSum -= $entry->amount;
                }
            }
            $entriesTrend[] = $localSum;
        }
        return $entriesTrend;
    }

    public function compundInterestTrend($initialInvest, $regularInvest, $interestRate, $years) {
        $compoundInterestArray = [];
        for($i=1; $i<$years+1; $i++) {
            $compoundInterestArray[] = $this->compundInterest($initialInvest, $regularInvest, $interestRate, $i);
        }
        return $compoundInterestArray;
    }

    public function compundInterest($initialInvest, $regularInvest, $interestRate, $years) {
        $n = 1; // times the rate is being compounded each year
        $compundInterestArray = [];
        $finalBalance = (int) round($initialInvest*pow(1+$interestRate,$years) + ($regularInvest*(1+$interestRate)*(pow((1+$interestRate), $years)-1))/($interestRate), 0);
        $invest = $initialInvest + $regularInvest * $years;
        $interest = (int) round($finalBalance - $invest, 0);
        $compundInterestArray[] = $invest;
        $compundInterestArray[] = $interest;
        return $compundInterestArray;
    }

}