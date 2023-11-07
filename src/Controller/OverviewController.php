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

    public function showOverview($navRoutes, $colorTheme, $userShortcut, $year, $customStartDate, $customEndDate, $timeInterval, $barchartScale) {
        $startDate = $this->chartController->getStartDate($timeInterval, $year);
        if($startDate === null) $startDate = $customStartDate; 
        $endDate = $year === date('Y') ? date('Y-m-d') : date($year . '-12-31');
        if ($customEndDate !== null) {
            $endDate = $customEndDate;
            $year = date('Y', strtotime($endDate));
        }

        $daysleft = calculateRemainingDays($year);
        $timespanAccount = $this->entryController->timespanFirstEntry();
        $revColors = $this->colorThemeController->giveChartColorsBudgetBook('rev', 1);
        $expColors = $this->colorThemeController->giveChartColorsBudgetBook('exp', 1);
        $revColorsTransparent = $this->colorThemeController->giveChartColorsBudgetBook('rev', 0.75);
        $expColorsTransparent = $this->colorThemeController->giveChartColorsBudgetBook('exp', 0.75);

        $dateArray = $this->chartController->dateArray($startDate, $endDate);
        $balances = $this->chartController->budgetbookBalances($startDate, $endDate);
        $fixedBalances = $this->chartController->fixedBalances($startDate, $endDate);
        $alltimeBalances = $this->chartController->alltimeBalances();
        
        $cashflowOverTimeinterval = $this->chartController->cashflowOverTimeinterval($startDate, $endDate);

        $revenuesTrendByCatC = $this->chartController->entryDataByTypeC($startDate, $endDate, 'rev');
        $revenuesByCatC = $this->chartController->summedEntryDataC($revenuesTrendByCatC);
        $revenuesByCatP = calculatePercentagesArray($revenuesByCatC);
        $revenuesByCatMonthlyAverageC = $this->chartController->summedEntryDataCAveragePerMonth($endDate, 'rev');
        $revenuesByCatMonthlyAverageP = calculatePercentagesArray($revenuesByCatMonthlyAverageC);
        $revenueCatsFixed = $this->entryController->entryTrendByEntrytypeFixed($startDate, $endDate, 'rev');
        
        $expendituresTrendByCatC = $this->chartController->entryDataByTypeC($startDate, $endDate, 'exp');
        $expendituresByCatC = $this->chartController->summedEntryDataC($expendituresTrendByCatC);
        $expendituresByCatP = calculatePercentagesArray($expendituresByCatC);
        $expendituresByCatMonthlyAverageC = $this->chartController->summedEntryDataCAveragePerMonth($endDate, 'exp');
        $expendituresByCatMonthlyAverageP = calculatePercentagesArray($expendituresByCatMonthlyAverageC);
        $expenditureCatsFixed = $this->entryController->entryTrendByEntrytypeFixed($startDate, $endDate, 'exp');

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