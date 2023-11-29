<?php 

namespace App\Controller;

use App\Controller\WDController;
use App\Controller\YearlyController;
use App\Controller\EntryController;
use App\Controller\ColorThemeController;
use App\Controller\ChartController;

class HomepageController extends AbstractController{

    public function __construct(
        protected WDController $wdController,
        protected YearlyController $yearlyController,
        protected EntryController $entryController,
        protected ColorThemeController $colorThemeController,
        protected ChartController $chartController) {}

    public function showHomepage($navRoutes, $colorTheme, $userShortcut, $year, $customStartMonth, $customEndMonth, $timeInterval, $chartColorSet) {
        $startDate = $this->chartController->getStartDate($timeInterval, $year);
        if($startDate === false & $customStartMonth !== null) $startDate = $customStartMonth . '-01'; elseif($startDate === false & $customStartMonth === null) $startDate = date('Y-m', strtotime("-1 month")) . '-01';
        $queryDate = $year === date('Y') ? date('Y-m') : date($year . '-m'); 
        if ($customEndMonth !== null) {
            $queryDate = $customEndMonth;
            $year = date('Y', strtotime($queryDate));
        }

        $currentWDTargetArrayC = $this->wdController->currentWDValues($queryDate, 'target'); 
        $currentWDActualArrayC = $this->wdController->currentWDValues($queryDate, 'actual');
        $currentTotalWealth = $this->wdController->currentTotalWealth($queryDate);
        $currentGoalSharesC = $this->chartController->currentGoalShares($year, $queryDate, 'wd');
        $goalsArray = $this->yearlyController->fetchCurrentGoals($year);
        $daysleft = calculateRemainingDays($year);
        $backgroundColor10 = $this->colorThemeController->giveChartColors($chartColorSet, 1)[0];
        $backgroundColor2 = $this->colorThemeController->giveChartColors($chartColorSet, 1)[1];
        $backgroundColorTransp10 = $this->colorThemeController->giveChartColors($chartColorSet, 0.75)[0];
        $backgroundColorTransp2 = $this->colorThemeController->giveChartColors($chartColorSet, 0.75)[1];
        $wdYC = $this->chartController->wdTrendArray('actual', $queryDate, $startDate);
        $wdYTargetActualC = $this->chartController->wdTrendArray('total-target-actual', $queryDate, $startDate);
        $donationsArrayC = $this->chartController->donationsValuesArray($year, $startDate, $queryDate);
        $donationEntries = $this->entryController->donationsTrend($startDate, $queryDate);
        $savingsArrayC = $this->chartController->wdTrendArray('actual-liquid', $queryDate, $startDate);
        $currentSavingsTargetArrayC = $this->wdController->currentWDValues($queryDate, 'target-liquid'); 
        $currentSavingsActualArrayC = $this->wdController->currentWDValues($queryDate, 'actual-liquid'); 
        $currentTotalLiquid = $this->wdController->currentTotalLiquid($queryDate);
        $currentSavingGoalSharesC = $this->chartController->currentGoalShares($year, $queryDate, 'liquid');

        // Calculate charts even if there are no balances yet for this month
        if(@end(end($wdYC)) !== date('M Y', strtotime($queryDate))) {
            $currentWDTargetArrayC = $this->wdController->currentWDValues(date('Y-m', strtotime("-1 month")), 'target'); 
            $currentWDActualArrayC = $this->wdController->currentWDValues(date('Y-m', strtotime("-1 month")), 'actual');
            $currentTotalWealth = $this->wdController->currentTotalWealth(date('Y-m', strtotime("-1 month")));
            $currentGoalSharesC = $this->chartController->currentGoalShares($year, date('Y-m', strtotime("-1 month")), 'wd');
            $wdYC = $this->chartController->wdTrendArray('actual', date('Y-m', strtotime("-1 month")), $startDate);
            $wdYTargetActualC = $this->chartController->wdTrendArray('total-target-actual', date('Y-m', strtotime("-1 month")), $startDate);
            $donationsArrayC = $this->chartController->donationsValuesArray($year, $startDate, date('Y-m', strtotime("-1 month")));
            $donationEntries = $this->entryController->donationsTrend($startDate, date('Y-m', strtotime("-1 month")));
            $savingsArrayC = $this->chartController->wdTrendArray('actual-liquid', date('Y-m', strtotime("-1 month")), $startDate);
            $currentSavingsTargetArrayC = $this->wdController->currentWDValues(date('Y-m', strtotime("-1 month")), 'target-liquid'); 
            $currentSavingsActualArrayC = $this->wdController->currentWDValues(date('Y-m', strtotime("-1 month")), 'actual-liquid'); 
            $currentTotalLiquid = $this->wdController->currentTotalLiquid(date('Y-m', strtotime("-1 month")));
            $currentSavingGoalSharesC = $this->chartController->currentGoalShares($year, date('Y-m', strtotime("-1 month")), 'liquid');
        }

        $currentWDTargetArrayP = calculatePercentagesArray($currentWDTargetArrayC);
        $currentWDActualArrayP = calculatePercentagesArray($currentWDActualArrayC);
        $currentGoalSharesP = calculatePercentagesArray($currentGoalSharesC);
        $donationsArrayP = calculatePercentagesArray($donationsArrayC);
        $currentSavingsTargetArrayP = calculatePercentagesArray($currentSavingsTargetArrayC);
        $currentSavingsActualArrayP = calculatePercentagesArray($currentSavingsActualArrayC);
        $currentSavingGoalSharesP = calculatePercentagesArray($currentSavingGoalSharesC);

        $this->render('homepage', [
            'year' => $year,
            'timeInterval' => $timeInterval,
            'startDate' => $startDate,
            'queryDate' => $queryDate,
            'navRoutes' => $navRoutes,
            'colorTheme' => $colorTheme,
            'userShortcut' => $userShortcut,
            'currentWDTargetArrayC' => $currentWDTargetArrayC,
            'currentWDTargetArrayP' => $currentWDTargetArrayP,
            'currentWDActualArrayC' => $currentWDActualArrayC,
            'currentWDActualArrayP' => $currentWDActualArrayP,
            'currentTotalWealth' => $currentTotalWealth,
            'currentGoalSharesC' => $currentGoalSharesC,
            'currentGoalSharesP' => $currentGoalSharesP,
            'goalsArray' => $goalsArray,
            'daysleft' => $daysleft,
            'chartColorSet' => $chartColorSet,
            'backgroundColor10' => $backgroundColor10,
            'backgroundColorTransp10' => $backgroundColorTransp10,
            'backgroundColor2' => $backgroundColor2,
            'backgroundColorTransp2' => $backgroundColorTransp2,
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
}