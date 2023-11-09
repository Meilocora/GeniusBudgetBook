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

    public function showOverview($navRoutes, $colorTheme, $userShortcut, $year, $customStartMonth, $customEndMonth, $timeInterval, $barchartScale) {
        $startDate = $this->chartController->getStartDate($timeInterval, $year);
        if($startDate === false & $customStartMonth !== null) $startDate = $customStartMonth . '-01'; elseif ($startDate === false & $customStartMonth === null) $startDate = date('Y-m', strtotime("-1 month")) . '-01';
        $endDate = $year === date('Y') ? date('Y-m-d') : date($year . '-m-t');
        if ($customEndMonth !== null) {
            $endDate = date($customEndMonth . '-t');
            $year = date('Y', strtotime($endDate));
        }

        $timespanAccount = $this->entryController->timespanFirstEntry();
        $timespanQuery = $this->chartController->getTimespanQueryDates($startDate, $endDate);

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
            'startDate' => $startDate,
            'endDate' => $endDate,
            'navRoutes' => $navRoutes,
            'colorTheme' => $colorTheme,
            'userShortcut' => $userShortcut,
            'timespanAccount' => $timespanAccount,
            'timespanQuery' => $timespanQuery,
            'barchartScale' => $barchartScale,
            'revColors' => $revColors,
            'expColors' => $expColors,
            'revColorsTransparent' => $revColorsTransparent,
            'expColorsTransparent' => $expColorsTransparent,
            
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