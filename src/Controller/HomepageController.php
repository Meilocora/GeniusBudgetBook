<?php 

namespace App\Controller;

use App\Controller\WDController;
use App\Controller\YearlyController;

class HomepageController extends AbstractController{

    public function __construct(
        protected WDController $wdController,
        protected YearlyController $yearlyController) {}

    public function showHomepage($navRoutes, $startDate) {
        $currentWDTargetArrayC = $this->wdController->currentWDTarget(); 
        $currentWDTargetArrayP = $this->calculatePercentagesArray($currentWDTargetArrayC);
        $currentWDActualArrayC = $this->wdController->currentWDActual();
        $currentWDActualArrayP = $this->calculatePercentagesArray($currentWDActualArrayC);
        $currentTotalWealth = $this->wdController->currentTotalWealth();
        $currentGoalSharesC = $this->currentGoalShares();
        $currentGoalSharesP = $this->calculatePercentagesArray($currentGoalSharesC);
        $goalsArray = $this->yearlyController->fetchCurrentGoals();
        $daysleft = $this->calculateRemainingDays();

        #TODO: Switch für Farben einbauen (Theme oder Bunt)
        $backgroundColor10 = ['rgb(20,113,73)', 'rgb(25,128,83)', 'rgb(33,149,99)', 'rgb(44,175,118)', 'rgb(54,189,128)', 'rgb(75,197,133)', 'rgb(101,208,141)', 'rgb(128,218,144)', 'rgb(142,221,145)', 'rgb(207,245,191)'];
        $backgroundColor2 = ['rgb(20,113,73)', 'rgb(207,245,191)'];
        $transparency = 0.75;
        $backgroundColorTransp10 = ["rgb(20,113,73,$transparency)", "rgb(25,128,83,$transparency)", "rgb(33,149,99,$transparency)", "rgb(44,175,118,$transparency)", "rgb(54,189,128,$transparency)", "rgb(75,197,133,$transparency)", "rgb(101,208,141,$transparency)", "rgb(128,218,144,$transparency)", "rgb(142,221,145,$transparency)", "rgb(207,245,191,$transparency)"];
        $backgroundColorTransp2 = ["rgb(20,113,73,$transparency)", "rgb(207,245,191,$transparency)"];
        $wdYC = $this->wdTrendArray('actual', $startDate);
        $wdYTargetActualC = $this->wdTrendArray('total-target-actual', $startDate);

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
            'backgroundColor10' => $backgroundColor10,
            'backgroundColorTransp10' => $backgroundColorTransp10,
            'backgroundColor2' => $backgroundColor2,
            'backgroundColorTransp2' => $backgroundColorTransp2,
            'startDate' => $startDate,
            'wdYC' => $wdYC,
            'wdYTargetActualC' => $wdYTargetActualC
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


    #TODO: DonationGoal... wenn Kommentar "donation" oder "Spende" enthält (groß oder klein)... Hinweis bei Registrierung!
                        // Liste mit allen Einträgen + 2x pie chart

    #TODO: SavingGoal... Alles, was zu liquiden Mitteln gehört... 2x pie-chart + 1x line-chart
}