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

    public function showOverview($navRoutes, $colorTheme, $userShortcut, $year, $timeInterval, $barchartScale) {
        $startDate = $this->chartController->getStartDate($timeInterval, $year);
        $daysleft = calculateRemainingDays($year);
        $timespanAccount = $this->entryController->timespanFirstEntry();
        $revColors = $this->colorThemeController->giveChartColorsBudgetBook('rev', 1);
        $expColors = $this->colorThemeController->giveChartColorsBudgetBook('exp', 1);
        $revColorsTransparent = $this->colorThemeController->giveChartColorsBudgetBook('rev', 0.75);
        $expColorsTransparent = $this->colorThemeController->giveChartColorsBudgetBook('exp', 0.75);

        $dateArray = $this->chartController->dateArray($startDate, $year);
        $balances = $this->chartController->budgetbookBalances($startDate, $year);
        $fixedBalances = $this->chartController->fixedBalances($startDate, $year);
        $alltimeBalances = $this->chartController->alltimeBalances();
        
        $cashflowOverTimeinterval = $this->chartController->cashflowOverTimeinterval($startDate, $year);

        $revenuesTrendByCatC = $this->chartController->entryDataByTypeC($startDate, $year, 'rev');
        $revenuesByCatC = $this->chartController->summedEntryDataC($revenuesTrendByCatC);
        $revenuesByCatP = calculatePercentagesArray($revenuesByCatC);
        $revenuesByCatMonthlyAverageC = $this->chartController->summedEntryDataCAveragePerMonth($year, 'rev');
        $revenuesByCatMonthlyAverageP = calculatePercentagesArray($revenuesByCatMonthlyAverageC);
        $revenueCatsFixed = $this->entryController->entryTrendByEntrytypeFixed($startDate, $year, 'rev');
        
        $expendituresTrendByCatC = $this->chartController->entryDataByTypeC($startDate, $year, 'exp');
        $expendituresByCatC = $this->chartController->summedEntryDataC($expendituresTrendByCatC);
        $expendituresByCatP = calculatePercentagesArray($expendituresByCatC);
        $expendituresByCatMonthlyAverageC = $this->chartController->summedEntryDataCAveragePerMonth($year, 'exp');
        $expendituresByCatMonthlyAverageP = calculatePercentagesArray($expendituresByCatMonthlyAverageC);
        $expenditureCatsFixed = $this->entryController->entryTrendByEntrytypeFixed($startDate, $year, 'exp');
       
        $this->render('budget-book/overview', [
            'year' => $year,
            'timeInterval' => $timeInterval,
            'navRoutes' => $navRoutes,
            'colorTheme' => $colorTheme,
            'userShortcut' => $userShortcut,
            'daysleft' => $daysleft,
            'timespanAccount' => $timespanAccount,
            'barchartScale' => $barchartScale,
            'revColors' => $revColors,
            'expColors' => $expColors,
            'revColorsTransparent' => $revColorsTransparent,
            'expColorsTransparent' => $expColorsTransparent,
            'startDate' => $startDate,
            'dateArray' => $dateArray,
            'balances' => $balances,
            'fixedBalances' => $fixedBalances,
            'alltimeBalances' => $alltimeBalances,
            'cashflowOverTimeinterval' => $cashflowOverTimeinterval,

            'revenuesTrendByCatC' => $revenuesTrendByCatC,
            'revenuesByCatC' => $revenuesByCatC,
            'revenuesByCatP' => $revenuesByCatP,
            'revenuesByCatMonthlyAverageC' => $revenuesByCatMonthlyAverageC,
            'revenuesByCatMonthlyAverageP' => $revenuesByCatMonthlyAverageP,
            'revenueCatsFixed' => $revenueCatsFixed,

            'expendituresTrendByCatC' => $expendituresTrendByCatC,
            'expendituresByCatC' => $expendituresByCatC,
            'expendituresByCatP' => $expendituresByCatP,
            'expendituresByCatMonthlyAverageC' => $expendituresByCatMonthlyAverageC,
            'expendituresByCatMonthlyAverageP' => $expendituresByCatMonthlyAverageP,
            'expenditureCatsFixed' => $expenditureCatsFixed,
            
        ]);
    }
}