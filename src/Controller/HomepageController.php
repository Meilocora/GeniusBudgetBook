<?php 

namespace App\Controller;

use App\Controller\WDController;
use App\Controller\YearlyController;
use App\Controller\EntryController;

class HomepageController extends AbstractController{

    public function __construct(
        protected WDController $wdController,
        protected YearlyController $yearlyController,
        protected EntryController $entryController) {}

    public function showHomepage($navRoutes, $year, $timeInterval, $colorTheme) {
        $queryDate = $year === date('Y') ? date('Y-m') : $year . '-12';
        $startDate = $this->getStartDate($timeInterval, $year);
        $currentWDTargetArrayC = $this->wdController->currentWDValues($queryDate, 'target'); 
        $currentWDTargetArrayP = calculatePercentagesArray($currentWDTargetArrayC);
        $currentWDActualArrayC = $this->wdController->currentWDValues($queryDate, 'actual');
        $currentWDActualArrayP = calculatePercentagesArray($currentWDActualArrayC);
        $currentTotalWealth = $this->wdController->currentTotalWealth($queryDate);
        $currentGoalSharesC = $this->currentGoalShares($year, $queryDate, 'wd');
        $currentGoalSharesP = calculatePercentagesArray($currentGoalSharesC);

        $goalsArray = $this->yearlyController->fetchCurrentGoals($year);
        $daysleft = calculateRemainingDays($year);
        $backgroundColor10 = $this->giveColors($colorTheme, 1)[0];
        $backgroundColor2 = $this->giveColors($colorTheme, 1)[1];
        $backgroundColorTransp10 = $this->giveColors($colorTheme, 0.75)[0];
        $backgroundColorTransp2 = $this->giveColors($colorTheme, 0.75)[1];
        $wdYC = $this->wdTrendArray('actual', $queryDate, $startDate);
        $wdYTargetActualC = $this->wdTrendArray('total-target-actual', $queryDate, $startDate);
        $donationsArrayC = $this->donationsValuesArray($year, $startDate);
        $donationsArrayP = calculatePercentagesArray($donationsArrayC);
        $donationEntries = $this->entryController->donationsTrend($startDate, $year);

        $savingsArrayC = $this->wdTrendArray('actual-liquid', $queryDate, $startDate);
        $currentSavingsTargetArrayC = $this->wdController->currentWDValues($queryDate, 'target-liquid'); 
        $currentSavingsTargetArrayP = calculatePercentagesArray($currentSavingsTargetArrayC);
        $currentSavingsActualArrayC = $this->wdController->currentWDValues($queryDate, 'actual-liquid'); 
        $currentSavingsActualArrayP = calculatePercentagesArray($currentSavingsActualArrayC);
        $currentTotalLiquid = $this->wdController->currentTotalLiquid($queryDate);
        $currentSavingGoalSharesC = $this->currentGoalShares($year, $queryDate, 'liquid');
        $currentSavingGoalSharesP = calculatePercentagesArray($currentSavingGoalSharesC);

        $this->render('homepage', [
            'year' => $year,
            'timeInterval' => $timeInterval,
            'navRoutes' => $navRoutes,
            'currentWDTargetArrayC' => $currentWDTargetArrayC,
            'currentWDTargetArrayP' => $currentWDTargetArrayP,
            'currentWDActualArrayC' => $currentWDActualArrayC,
            'currentWDActualArrayP' => $currentWDActualArrayP,
            'currentTotalWealth' => $currentTotalWealth,
            'currentGoalSharesC' => $currentGoalSharesC,
            'currentGoalSharesP' => $currentGoalSharesP,
            'goalsArray' => $goalsArray,
            'daysleft' => $daysleft,
            'colorTheme' => $colorTheme,
            'backgroundColor10' => $backgroundColor10,
            'backgroundColorTransp10' => $backgroundColorTransp10,
            'backgroundColor2' => $backgroundColor2,
            'backgroundColorTransp2' => $backgroundColorTransp2,
            'startDate' => $startDate,
            'wdYC' => $wdYC,
            'wdYTargetActualC' => $wdYTargetActualC,
            'donationsArrayC' => $donationsArrayC,
            'donationsArrayP' => $donationsArrayP,
            'donationEntries' => $donationEntries,
            'savingsArrayC' => $savingsArrayC,
            'currentSavingsTargetArrayC' => $currentSavingsTargetArrayC,
            'currentSavingsTargetArrayP' => $currentSavingsTargetArrayP,
            'currentSavingsActualArrayC' => $currentSavingsActualArrayC,
            'currentSavingsActualArrayP' => $currentSavingsActualArrayP,
            'currentTotalLiquid' => $currentTotalLiquid,
            'currentSavingGoalSharesC' => $currentSavingGoalSharesC,
            'currentSavingGoalSharesP' => $currentSavingGoalSharesP,
        ]);
    }

    public function getStartDate($timeInterval, $year) {
        switch ($timeInterval) {
            case 'YTD':
                return $year . '-01-01';
            case 'YOY':
                #TODO: was passiert beim Dezember 2022?!
                return (int)$year -1 . '-' . date('m',strtotime("-11 month")) . '-01';
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

    public function giveColors($colorTheme, $transparency) {
        $colorsArray = [];
        switch ($colorTheme) {
            case 'default':
                $colors10 = ["rgb(20,113,73,$transparency)", "rgb(25,128,83,$transparency)", "rgb(33,149,99,$transparency)", "rgb(44,175,118,$transparency)", "rgb(54,189,128,$transparency)", "rgb(75,197,133,$transparency)", "rgb(101,208,141,$transparency)", "rgb(128,218,144,$transparency)", "rgb(142,221,145,$transparency)", "rgb(207,245,191,$transparency)"];
                $colors2 = ["rgb(20,113,73,$transparency)", "rgb(207,245,191,$transparency)"];
                break;
            case 'colorful':
                $colors10 = ["rgb(255,0,0,$transparency)", "rgb(255,127,0,$transparency)", "rgb(255,255,0,$transparency)", "rgb(127,255,0,$transparency)", "rgb(0,255,0,$transparency)", "rgb(0,255,127,$transparency)", "rgb(0,255,255,$transparency)", "rgb(0,127,255,$transparency)", "rgb(0,0,255,$transparency)", "rgb(127,0,255,$transparency)"];
                $colors2 = ["rgb(255,0,0,$transparency)", "rgb(127,0,255,$transparency)"];
                break;
            }
        $colorsArray[] = $colors10;
        $colorsArray[] = $colors2;
        return $colorsArray;
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

    #TODO: SavingGoal... Alles, was zu liquiden Mitteln gehört... 2x pie-chart + 1x line-chart
}