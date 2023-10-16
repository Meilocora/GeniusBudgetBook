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

    public function showHomepage($navRoutes, $startDate, $colorTheme) {
        $currentWDTargetArrayC = $this->wdController->currentWDTarget(); 
        $currentWDTargetArrayP = $this->calculatePercentagesArray($currentWDTargetArrayC);
        $currentWDActualArrayC = $this->wdController->currentWDActual();
        $currentWDActualArrayP = $this->calculatePercentagesArray($currentWDActualArrayC);
        $currentTotalWealth = $this->wdController->currentTotalWealth();
        $currentGoalSharesC = $this->currentGoalShares();
        $currentGoalSharesP = $this->calculatePercentagesArray($currentGoalSharesC);
        $goalsArray = $this->yearlyController->fetchCurrentGoals();
        $daysleft = $this->calculateRemainingDays();
        $backgroundColor10 = $this->giveColors($colorTheme, 1)[0];
        $backgroundColor2 = $this->giveColors($colorTheme, 1)[1];
        $backgroundColorTransp10 = $this->giveColors($colorTheme, 0.75)[0];
        $backgroundColorTransp2 = $this->giveColors($colorTheme, 0.75)[1];
        $wdYC = $this->wdTrendArray('actual', $startDate);
        $wdYTargetActualC = $this->wdTrendArray('total-target-actual', $startDate);
        $donationsArrayC = $this->donationsValuesArray();
        $donationsArrayP = $this->calculatePercentagesArray($donationsArrayC);

        $this->render('homepage', [
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
            'donationsArrayP' => $donationsArrayP
        ]);
    }

    public function currentGoalShares() {
        $currentGoalsArray = $this->yearlyController->fetchCurrentGoals();
        $currentTotalWealth = $this->wdController->currentTotalWealth();
        $totalWealthGoalShares = [];
        if($currentTotalWealth < $currentGoalsArray['totalwealthgoal']) {
            $totalWealthGoalShares['Current total wealth'] = $currentTotalWealth;
            $totalWealthGoalShares['Missing wealth'] = $currentGoalsArray['totalwealthgoal'] - $currentTotalWealth;
        } else {
            $totalWealthGoalShares['Current total wealth'] = $currentTotalWealth;
            $totalWealthGoalShares['Missing wealth'] = 0;
        }
        arsort($totalWealthGoalShares);  
        return $totalWealthGoalShares;
    }

    #TODO: In functions auslagern
    public function calculatePercentagesArray($array) {
        $sum = array_sum($array);
        $percentagesArray= [];
        foreach($array AS $key => $value) {
            $percentagesArray[$key] = round($value/$sum*100, 2);
        }
        return $percentagesArray;
    }

    public function calculateRemainingDays() {
        $year = date('Y');
        $yearEnd = strtotime("31 December ${year}");
        $today = strtotime(date('Y-m-d'));
        $timeleft = $yearEnd-$today;
        $daysleft = round((($timeleft/24)/60)/60); 
        return $daysleft;
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

    public function wdTrendArray($dataSet, $startDate) {
        $twoDWDArray = $this->wdController->wdTrend($startDate, $dataSet);
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
    }

    public function donationsValuesArray() {
        $startDate = date('Y-m-d', strtotime('first day of january this year'));
        $donationEntries = $this->entryController->donationsTrend($startDate);
        $currentGoalsArray = $this->yearlyController->fetchCurrentGoals();
        $donationsValuesArray = [];
        $donationsActual = 0;
        foreach($donationEntries AS $entry) {
            $donationsActual += $entry->amount;
        }
        $donationsValuesArray[] = $donationsActual;
        $donationsValuesArray[] = max($currentGoalsArray['donationgoal'] - $donationsActual ,0);
        return $donationsValuesArray;
    }

    #TODO: SavingGoal... Alles, was zu liquiden Mitteln gehört... 2x pie-chart + 1x line-chart
}