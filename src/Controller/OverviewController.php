<?php 

namespace App\Controller;

use App\Controller\EntryController;
use App\Controller\ColorThemeController;
use App\Controller\ChartController;

class OverviewController extends AbstractController{

    public function __construct(
        protected EntryController $entryController,
        protected ColorThemeController $colorThemeController,
        protected ChartController $chartController
    ) {}

    public function showOverview($navRoutes, $colorTheme, $userShortcut, $year, $timeInterval, $chartColorSet) {
        $queryDate = $year === date('Y') ? date('Y-m') : $year . '-12';
        $startDate = $this->chartController->getStartDate($timeInterval, $year);
        $daysleft = calculateRemainingDays($year);
        $timespanAccount = $this->entryController->timespanFirstEntry();
        $backgroundColor10 = $this->colorThemeController->giveChartColors($chartColorSet, 1)[0];
        $backgroundColor2 = $this->colorThemeController->giveChartColors($chartColorSet, 1)[1];
        $backgroundColorTransp10 = $this->colorThemeController->giveChartColors($chartColorSet, 0.75)[0];
        $backgroundColorTransp2 = $this->colorThemeController->giveChartColors($chartColorSet, 0.75)[1];

        $balances = $this->chartController->budgetbookBalances($startDate, $year);
        $fixedBalances = $this->chartController->fixedBalances($startDate, $year);
        $alltimeBalances = $this->chartController->alltimeBalances();
        
        // all fixed revenues entries
        // all revenue entries
        // all fixed expenditure entries
        // all expenditure entries

        // median of totalbalance, revenues, expenditures by timeframe

        // all entries by timeframe
        $this->render('budget-book/overview', [
            'year' => $year,
            'timeInterval' => $timeInterval,
            'navRoutes' => $navRoutes,
            'colorTheme' => $colorTheme,
            'userShortcut' => $userShortcut,
            'daysleft' => $daysleft,
            'timespanAccount' => $timespanAccount,
            'chartColorSet' => $chartColorSet,
            'backgroundColor10' => $backgroundColor10,
            'backgroundColorTransp10' => $backgroundColorTransp10,
            'backgroundColor2' => $backgroundColor2,
            'backgroundColorTransp2' => $backgroundColorTransp2,
            'startDate' => $startDate,
            'balances' => $balances,
            'fixedBalances' => $fixedBalances,
            'alltimeBalances' => $alltimeBalances,
            
        ]);
    }
}