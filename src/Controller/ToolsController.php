<?php 

namespace App\Controller;

use App\CashFlowPlanner\CashFlowPlannerRepository;
use App\Controller\ChartController;
use App\Controller\ColorThemeController;
use App\Controller\WDController;

class ToolsController extends AbstractController{

    public function __construct(
        protected CashFlowPlannerRepository $cashFlowPlannerRepository,
        protected ChartController $chartController,
        protected ColorThemeController $colorThemeController,
        protected WDController $wDController) {}

    public function showTools($navRoutes, $colorTheme, $userShortcut, $millionaireAssumption, $cashFlowData, $initialCapital, $regularInvest, $investDuration, $interestRate) {
        // WHEN MILLIONAIRE
        if($this->whenMillionaire($millionaireAssumption) !== null) {
            $millionaireYears = $this->whenMillionaire($millionaireAssumption)[0];
            $millionaireTrend = $this->whenMillionaire($millionaireAssumption)[1];
            $millinaireTotalBalances = $this->whenMillionaire($millionaireAssumption)[2];
        } else {
            $millionaireYears = 0;
            $millionaireTrend = null;
            $millinaireTotalBalances = [0];
        }
        $chartColors = $this->colorThemeController->giveChartColors('default', 0.75);
        // CASHFLOW PLANNER
        if($cashFlowData !== null) $this->updateCashflowPlanner($cashFlowData);
        if(!empty($this->fetch())) {
                $cashFlowPlanner = $this->fetch()[0];
                $balances = $this->calculateBalances($cashFlowPlanner);
        } else {
                $cashFlowPlanner = null;
                $balances = null;
        }
        // COMPOUND INTEREST CALCULATOR
        $yearsArray = $this->chartController->yearsArray($investDuration);
        $compoundInterestArray = $this->chartController->compundInterestTrend($initialCapital, $regularInvest, $interestRate, $investDuration);
        $this->render('tools', [
            'navRoutes' => $navRoutes,
            'colorTheme' => $colorTheme,
            'userShortcut' => $userShortcut,
            'chartColors' => $chartColors,
            'millionaireAssumption' => $millionaireAssumption,
            'millionaireYears' => $millionaireYears,
            'millionaireTrend' => $millionaireTrend,
            'millinaireTotalBalances' => $millinaireTotalBalances,
            'cashFlowPlanner' => $cashFlowPlanner,
            'balances' => $balances,
            'initialCapital' => $initialCapital,
            'regularInvest' => $regularInvest,
            'interestRate' => $interestRate,
            'investDuration' => $investDuration,
            'yearsArray' => $yearsArray,
            'compoundInterestArray' => $compoundInterestArray,
        ]);
    }

    public function fetch() {
        $tableFound = $this->cashFlowPlannerRepository->tableExisting();
        if(!$tableFound) {
            $this->cashFlowPlannerRepository->generateTable();
        } 
        return $this->cashFlowPlannerRepository->fetch();
    }

    public function updateCashflowPlanner($cashFlowData) {
        $queryArray = [];
        $tableData = $this->cashFlowPlannerRepository->fetch();
        if(!$tableData) {
            $valuesArray = [' VALUES ('];
            foreach($cashFlowData AS $key => $value) {
                $valuesArray[] = "'{$value}', ";
            }
            $valuesString = substr(implode($valuesArray), 0, -2) . ")";
            $columnsArray = ["("];
            for($i=1; $i<11; $i++) {
                $columnsArray[] = "`revName{$i}`, `revAmount{$i}`, ";
                $columnsArray[] = "`expName{$i}`, `expAmount{$i}`, ";
            }
            $columnsString = substr(implode($columnsArray), 0, -2) . ")";
            $queryString = $columnsString . $valuesString;
            $this->cashFlowPlannerRepository->create($queryString);
            return;
        } else{
            foreach($cashFlowData AS $key => $value) {
                $queryArray[] = "`{$key}` = '{$value}', ";
            }
            $queryString = substr(implode($queryArray), 0, -2);
            $this->cashFlowPlannerRepository->update($queryString);
            return;
        }
    }

    public function calculateBalances($cashFlowPlanner) {
        $balances = [];
        $revenues = 0;
        $expenditures = 0;
        foreach($cashFlowPlanner AS $key => $value) {
            if(preg_match('/^revAmount.*$/', $key)) $revenues += $value;
            if(preg_match('/^expAmount.*$/', $key)) $expenditures -= $value;
        }
        $balances[] = $revenues;
        $balances[] = $expenditures;
        $balances[] = $revenues + $expenditures;
        return $balances;
    }

    public function whenMillionaire($millionaireAssumption) {
        $firstBalanceDate = $this->wDController->firstWDBalanceDate();
        if($firstBalanceDate === null) return;
        $wdYC = $this->chartController->wdTrendArray('actual', date('Y-m'), $firstBalanceDate);
        $timeFactor = min(1, round((strtotime(date('Y-m')) - strtotime($firstBalanceDate))/60/60/24/30.42/12, 2));
        array_pop($wdYC);
        $catsArray = [];
        $increaseArray = [];
        $catValuesArray = [];
        foreach($wdYC AS $category) {
            $catsArray[] = $category[0];
            $increaseArray[] = round(($this->calculateIncrease($millionaireAssumption, $category[1], $category[sizeof($category)-1]))/$timeFactor, 2);
            $catValuesArray[] = $category[sizeof($category)-1];
        }
        $totalBalance = array_sum($catValuesArray);
        $increaseTestArray = [];
        for($i=0; $i<sizeof($catValuesArray); $i++) {
            if($millionaireAssumption === 'linear') {
                $increaseTestArray[] = $catValuesArray[$i] + $increaseArray[$i];
            } elseif($millionaireAssumption === 'exponentially') {
                $increaseTestArray[] = $catValuesArray[$i] + $catValuesArray[$i] * $increaseArray[$i];
            }
        }
        $secondBalance = array_sum($increaseTestArray);

        $allCatValuesArray = [];
        $totalBalanceArray = [];
        if($secondBalance > $totalBalance) {
            $counter = 0;
            while($totalBalance < 1000000) {
                $counter++;
                for($i=0; $i<sizeof($catValuesArray); $i++) {
                    if($millionaireAssumption === 'linear') {
                        $catValuesArray[$i] += $increaseArray[$i];
                    } elseif($millionaireAssumption === 'exponentially') {
                        $catValuesArray[$i] += round($catValuesArray[$i] * $increaseArray[$i], 0);
                    }
                }
                $totalBalance = array_sum($catValuesArray);
                $allCatValuesArray[] = $catValuesArray;
            }
            $totalBalanceArray = $catValuesArray;
            $totalBalanceArray[] = array_sum($catValuesArray);
        } else {
            return null;
        }
        array_unshift($allCatValuesArray, $catsArray);
        $returnArray = [];
        $returnArray[] = $counter;
        $returnArray[] = $allCatValuesArray; 

        $catsArray[] = 'Total Balance';
        for($i=0; $i<sizeof($catsArray); $i++) {
            $totalBalancesArray[$catsArray[$i]] = $totalBalanceArray[$i];
        }
        $returnArray[] = $totalBalancesArray;
       return $returnArray;
    }

    public function calculateIncrease($millionaireAssumption, $value1, $value2) {
        if($millionaireAssumption === 'linear') {
            return $value2 - $value1;
        } elseif($millionaireAssumption === 'exponentially') {
            return $value2 / $value1 - 1;  // use this to calculate with percentages
        }
    }
    
}