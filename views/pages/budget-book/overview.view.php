<div class="scroll-container">
    <div class="scroll-element">
        <a href="#header-banner-name"><img src="./img/arrow_up.png" alt="Arrow symbol that points upwards" height='20px' width='20px'></a>    
        <a href="#cashflowContainer" class="scroll1">Total Cashflow</a>
        <a href="#revenuesContainer" class="scroll2">Revenues</a>
        <a href="#expendituresContainer" class="scroll3">Expenditures</a>
        <a href="#footer"><img src="./img/arrow_down.png" alt="Arrow symbol that points downwards" height='20px' width='20px'></a>
    </div>
</div>
<div class="adjustments-container">
    <img src="./img/gear.png" alt="Symbol of a gear" height='40px' width='40px' class="adjustmentsSymbol">
    <div class="adjustments-box" id="overviewBox">
        <div class="settings-row">
            <span>Choose a year:</span>
        </div>
        <div class="settings-row">
            <form action="./?route=overview" method="post">
                <input type="number" name="year" min="1970" max="<?php echo date('Y'); ?>" value="<?php echo $year; ?>">
                <input type='image' src='./img/checkmark.png' alt='Checkmark symbol' height='20px' width='20px'>
            </form> 
        </div>
        <div class="settings-row">
            <span>Change timeinterval:</span>
        </div>
        <div class="settings-row">
            <form action="./?route=overview" method="post" class="chartScopeForm">
                <input type="hidden" name="timeInterval" value="YTD">
                <input type="submit" value="YTD" 
                class="<?php if($timeInterval === 'YTD') echo 'chosen'; ?>">
            </form>
            <form action="./?route=overview" method="post" class="chartScopeForm">
                <input type="hidden" name="timeInterval" value="YOY">
                <input type="submit" value="YoY" 
                class="<?php if($timeInterval === 'YOY') echo 'chosen'; ?>">
            </form>
            <form action="./?route=overview" method="post" class="chartScopeForm">
                <input type="hidden" name="timeInterval" value="ALL">
                <input type="submit" value="All" 
                class="<?php if($timeInterval === 'ALL') echo 'chosen'; ?>">
            </form>
        </div>
        <div class="customDatesBox">
            <div class="settings-row">
                <form action="./?route=overview" method="post" class="chartScopeForm">
                    <span>Custom date</span>
            </div>
            <div class="settings-row">
                    <input type="hidden" name="timeInterval" value="Custom">
                    <input type="month" name="customStartMonth" max="<?php echo date('Y-m', strtotime("-1 month")); ?>" value="<?php echo date('Y-m', strtotime($startDate)); ?>" required>
                    <span>-</span>
                    <input type="month" name="customEndMonth" max="<?php echo date('Y-m'); ?>" value="<?php echo date('Y-m', strtotime($endDate)); ?>" required>
                    <input type='image' src='./img/checkmark.png' alt='Checkmark symbol' height='30px' width='30px'>
                </form>
            </div>
        </div>
        <div class="settings-row">
            <span>Change bar-chart scale:</span>
        </div>
        <div class="settings-row">
            <form action="./?route=overview" method="post" class="chartScopeForm">
                <input type="hidden" name="barchartScale" value="linear">
                <input type="submit" value="linear" 
                class="<?php if($barchartScale === 'linear') echo 'chosen'; ?>">
            </form>
            <form action="./?route=overview" method="post" class="chartScopeForm">
                <input type="hidden" name="barchartScale" value="logarithmic">
                <input type="submit" value="log" 
                class="<?php if($barchartScale === 'logarithmic') echo 'chosen'; ?>">
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="./src/JS/jQuery/jQuery.js" defer></script>
<script type="text/javascript" src="./src/JS/jQuery/jquery.waypoints.min.js" defer></script>
<script type="module">
    'use strict';
    import "./src/JS/jQuery/jquery.waypoints.min.js";
    let scroll1 = document.querySelector('.scroll1');
    let scroll2 = document.querySelector('.scroll2');
    let scroll3 = document.querySelector('.scroll3');
    let waypoint1 = new Waypoint({
            element: document.getElementById('cashflowContainer'),
            handler: function(direction) {
                scroll1.setAttribute("class", "scrollActive");
                scroll2.removeAttribute("class");
                scroll3.removeAttribute("class");
            }
    });
    let waypoint2 = new Waypoint({
            element: document.getElementById('revenuesContainer'),
            handler: function(direction) {
                scroll1.removeAttribute("class");
                scroll2.setAttribute("class", "scrollActive");
                scroll3.removeAttribute("class");
            }
    });
    let waypoint3 = new Waypoint({
            element: document.getElementById('expendituresContainer'),
            handler: function(direction) {
                scroll1.removeAttribute("class");
                scroll2.removeAttribute("class");
                scroll3.setAttribute("class", "scrollActive");
            }
    });
</script>
<section class="chart-view-container">
    <!-- ++++++++++ CASHFLOW-AREA ++++++++++ -->
    <div class="charts-container" id="cashflowContainer">
        <div class="charts-container-row">
            <h1>Total Cashflow</h1>
        </div>
        <div class="charts-container-row">
            <div class="report <?php if($balances['totalCashflow'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Total cashflow
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['totalCashflow'], '0', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report <?php if($fixedBalances['revenues']+$fixedBalances['expenditures'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Fixed cashflow
                </span>
                <span class="report-content">
                    <?php echo number_format($fixedBalances['revenues']+$fixedBalances['expenditures'], '0', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report <?php if($balances['totalCashflow']/$timespanQuery > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Cashflow p.d.
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['totalCashflow']/$timespanQuery, '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report neutral">
                <span class="report-label">
                    Avg cashflow
                </span>
                <span class="report-content">
                    <?php echo number_format(($alltimeBalances['totalCashflow']/$timespanAccount*$timespanQuery), '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report <?php if(($balances['totalCashflow']/($alltimeBalances['totalCashflow']/$timespanAccount*$timespanQuery)-1)*100 > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Compared to avg.
                </span>
                <span class="report-content">
                    <?php if(($balances['totalCashflow']/($alltimeBalances['totalCashflow']/$timespanAccount*$timespanQuery)-1)*100 > 0) echo "+"; ?>
                    <?php echo number_format(($balances['totalCashflow']/($alltimeBalances['totalCashflow']/$timespanAccount*$timespanQuery)-1)*100, '2', ',', '.') . '% '; ?>
                </span>
            </div>
        </div>
        <div class="charts-container-row">
            <canvas id="cashflowTrendChart" width="1100px" height="450px"></canvas>
        </div>
        <div class="charts-container-row">
            <canvas id="cashflowBarChart" width="1100px" height="450px"></canvas>
        </div>
    </div>
    <!-- ++++++++++ REVENUE-AREA ++++++++++ -->
    <div class="charts-container" id="revenuesContainer">
        <div class="charts-container-row">
            <h1>Revenues</h1>
        </div>
        <div class="charts-container-row">
            <div class="report positive">
                <span class="report-label">
                    Total revenues
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['revenues'], '0', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report positive">
                <span class="report-label">
                    Fixed share
                </span>
                <span class="report-content">
                    <?php echo number_format($fixedBalances['revenues'], '0', ',', '.') . '€ '; ?>
                    &nbsp / &nbsp
                    <?php if($balances['revenues'] !== 0) echo number_format(($fixedBalances['revenues']/$balances['revenues'])*100, '2', ',', '.') . '% '; ?>
                </span>
            </div>
            <div class="report positive">
                <span class="report-label">
                    Revenues p.d.
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['revenues']/$timespanQuery, '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report neutral">
                <span class="report-label">
                    Avg revenues
                </span>
                <span class="report-content">
                    <?php echo number_format($alltimeBalances['revenues']/$timespanAccount*$timespanQuery, '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report <?php if(($balances['revenues']/($alltimeBalances['revenues']/$timespanAccount*$timespanQuery)-1)*100 > 0) echo "positive"; else echo "negative"; ?>">
                <span class="report-label">
                    Compared to avg.
                </span>
                <span class="report-content">
                    <?php if(($balances['revenues']/($alltimeBalances['revenues']/$timespanAccount*$timespanQuery)-1)*100 > 0) echo "+"; ?>
                    <?php echo number_format(($balances['revenues']/($alltimeBalances['revenues']/$timespanAccount*$timespanQuery)-1)*100, '2', ',', '.') . '% '; ?>
                </span>
            </div>
        </div>
        <div class="charts-container-row">
            <canvas id="revDoughnut" width="600px" height="400px"></canvas>
            <canvas id="revDoughnutAverage" width="600px" height="400px"></canvas>
        </div>  
        <div class="charts-container-row">
            <canvas id="revTrendChart" width="1100px" height="450px"></canvas>
        </div>
        <div class="charts-container-row">
            <canvas id="revBarChart" width="1100px" height="450px"></canvas>
        </div>
    </div>
    <!-- ++++++++++ EXPENDITURE-AREA ++++++++++ -->
    <div class="charts-container" id="expendituresContainer">
        <div class="charts-container-row">
            <h1>Expenditures</h1>
        </div>
        <div class="charts-container-row">
            <div class="report negative">
                <span class="report-label">
                    Total expenditures
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['expenditures'], '0', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report negative">
                <span class="report-label">
                    Fixed share
                </span>
                <span class="report-content">
                    <?php echo number_format($fixedBalances['expenditures'], '0', ',', '.') . '€ '; ?>
                    &nbsp / &nbsp
                    <?php if($fixedBalances['expenditures'] !== $balances['expenditures']) echo number_format(($fixedBalances['expenditures']/$balances['expenditures'])*100, '2', ',', '.') . '% '; else echo '100%'; ?>
                </span>
            </div>
            <div class="report negative">
                <span class="report-label">
                    Expenditures p.d.
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['expenditures']/$timespanQuery, '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report neutral">
                <span class="report-label">
                    Avg expenditures
                </span>
                <span class="report-content">
                    <?php echo number_format($alltimeBalances['expenditures']/$timespanAccount*$timespanQuery, '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report <?php if(($balances['expenditures']/($alltimeBalances['expenditures']/$timespanAccount*$timespanQuery)-1)*100 > 0) echo "negative"; else echo "positive" ?>">
                <span class="report-label">
                    Compared to avg.
                </span>
                <span class="report-content">
                    <?php if(($balances['expenditures']/($alltimeBalances['expenditures']/$timespanAccount*$timespanQuery)-1)*100 > 0) echo "+"; ?>
                    <?php echo number_format(($balances['expenditures']/($alltimeBalances['expenditures']/$timespanAccount*$timespanQuery)-1)*100, '2', ',', '.') . '% '; ?>
                </span>
            </div>
        </div>
        <div class="charts-container-row">
            <canvas id="expDoughnut" width="600px" height="400px"></canvas>
            <canvas id="expDoughnutAverage" width="600px" height="400px"></canvas>
        </div>  
        <div class="charts-container-row">
            <canvas id="expTrendChart" width="1100px" height="450px"></canvas>
        </div>
        <div class="charts-container-row">
            <canvas id="expBarChart" width="1100px" height="450px"></canvas>
        </div>
    </div>
</section>

<script type="module">
    "use strict";
    import ChartGenerator from "./src/JS/Classes/ChartGenerator.js";
    let chartGenerator = new ChartGenerator();
    let revColors = [<?php foreach($revColors[0] AS $color) echo "'$color', "; ?>]
    let expColors = [<?php foreach($expColors[0] AS $color) echo "'$color', "; ?>]
    let revColorsTransparent = [<?php foreach($revColorsTransparent[0] AS $color) echo "'$color', "; ?>]
    let expColorsTransparent = [<?php foreach($expColorsTransparent[0] AS $color) echo "'$color', "; ?>]
    let xAxisLabels = [<?php foreach($dateArray AS $date) echo "'$date', "; ?>];
    let yScale = <?php echo "'${barchartScale}'"; ?>;
    // ++++++++++ CASHFLOW-AREA ++++++++++ \\
    let cashflowCategoryLabels = ['Cashflow', 'Revenues', 'Expenditures'];
    let cashflowData1 = [<?php for($x=1; $x<sizeof($dateArray)+1; $x++) echo "{$cashflowOverTimeinterval['balances'][$x]}, "; ?>];
    let cashflowData2 = [<?php for($x=1; $x<sizeof($dateArray)+1; $x++) echo "{$cashflowOverTimeinterval['revenues'][$x]}, "; ?>];
    let cashflowData3 = [<?php for($x=1; $x<sizeof($dateArray)+1; $x++) echo "{$cashflowOverTimeinterval['expenditures'][$x]}, "; ?>];
    chartGenerator.generateLineChartCashflow('cashflowTrendChart', 'Trend of cashflow', xAxisLabels, cashflowCategoryLabels, cashflowData1, cashflowData2, cashflowData3);
    
    let labelArray = [<?php foreach($revenuesByCatC AS $key => $value) echo "'$key'" . ", "; ?> <?php foreach($expendituresByCatC AS $key => $value) echo "'$key'" . ", "; ?>];
    let cashflowrevdataregular = [<?php for($i=0;$i<sizeof($revenueCatsFixed);$i++) echo "{$revenueCatsFixed[$i]['regular']}, "; ?>];
    let cashflowrevdatafixed = [<?php  for($i=0;$i<sizeof($revenueCatsFixed);$i++) echo "{$revenueCatsFixed[$i]['fixed']}, "; ?>];
    let revdifference = labelArray.length - cashflowrevdataregular.length;
    for (let i=0; i<revdifference; i++) {
        cashflowrevdataregular.push(0);
        cashflowrevdatafixed.push(0);
    }
    let cashflowexpdataregular = [<?php for($i=0;$i<sizeof($expenditureCatsFixed);$i++) echo "{$expenditureCatsFixed[$i]['regular']}, "; ?>];
    let cashflowexpdatafixed = [<?php  for($i=0;$i<sizeof($expenditureCatsFixed);$i++) echo "{$expenditureCatsFixed[$i]['fixed']}, "; ?>];
    let expdifference = labelArray.length - cashflowexpdataregular.length;
    for (let i=0; i<expdifference; i++) {
        cashflowexpdataregular.unshift(0);
        cashflowexpdatafixed.unshift(0);
    }
    if (yScale === 'logarithmic') {
        cashflowexpdataregular = cashflowexpdataregular.map(Math.abs);
        cashflowexpdatafixed = cashflowexpdatafixed.map(Math.abs);
    }
    
    chartGenerator.generateBarChart('cashflowBarChart', yScale,  ['fixed revenues', 'regular revenues', 'fixed expenditures', 'regular expenditures'], 'Summed up cashflow categories', revColors, expColors, labelArray, cashflowrevdataregular, cashflowrevdatafixed, cashflowexpdataregular, cashflowexpdatafixed);
    // ++++++++++ REVENUE-AREA ++++++++++ \\
    let revLabelArray = [<?php foreach($revenuesByCatC AS $key => $value) echo "'$key'" . ", "; ?> ];
    let revDataArrayCurrency = [<?php foreach($revenuesByCatC AS $key => $value) echo "$value" . ", "; ?>];
    let revDataArrayPercentages = [<?php foreach($revenuesByCatP AS $key => $value) echo "$value" . ", "; ?>];
    chartGenerator.generateDoughnutChart('revDoughnut', revLabelArray, revDataArrayCurrency, revDataArrayPercentages, revColors, 'left', 'Summed up revenues by categories', true);

    let revDataArrayAvgCurrency = [<?php foreach($revenuesByCatMonthlyAverageC AS $key => $value) echo "$value" . ", "; ?>];
    let revDataArrayAvgPercentages = [<?php foreach($revenuesByCatMonthlyAverageP AS $key => $value) echo "$value" . ", "; ?>];
    chartGenerator.generateDoughnutChart('revDoughnutAverage', revLabelArray, revDataArrayAvgCurrency, revDataArrayAvgPercentages, revColors, 'right', 'Average monthly revenues by categories', true);

    let revTrendCatLabels = [<?php for($x=0; $x<sizeof($revenuesTrendByCatC); $x++) echo "'{$revenuesTrendByCatC[$x][0]}', "; ?>];
    let revTrendDataCat1 = [<?php if(isset($revenuesTrendByCatC[0][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "{$revenuesTrendByCatC[0][$x]}, "; ?>];
    let revTrendDataCat2 = [<?php if(isset($revenuesTrendByCatC[1][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$revenuesTrendByCatC[1][$x]}', "; ?>];
    let revTrendDataCat3 = [<?php if(isset($revenuesTrendByCatC[2][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$revenuesTrendByCatC[2][$x]}', "; ?>];
    let revTrendDataCat4 = [<?php if(isset($revenuesTrendByCatC[3][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$revenuesTrendByCatC[3][$x]}', "; ?>];
    let revTrendDataCat5 = [<?php if(isset($revenuesTrendByCatC[4][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$revenuesTrendByCatC[4][$x]}', "; ?>];
    let revTrendDataCat6 = [<?php if(isset($revenuesTrendByCatC[5][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$revenuesTrendByCatC[5][$x]}', "; ?>];
    let revTrendDataCat7 = [<?php if(isset($revenuesTrendByCatC[6][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$revenuesTrendByCatC[6][$x]}', "; ?>];
    let revTrendDataCat8 = [<?php if(isset($revenuesTrendByCatC[7][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$revenuesTrendByCatC[7][$x]}', "; ?>];
    let revTrendDataCat9 = [<?php if(isset($revenuesTrendByCatC[8][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$revenuesTrendByCatC[8][$x]}', "; ?>];
    let revTrendDataCat10 = [<?php if(isset($revenuesTrendByCatC[9][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$revenuesTrendByCatC[9][$x]}', "; ?>];
    chartGenerator.generateLineChart('revTrendChart', 'Cumulative trend of revenues', revColorsTransparent, 0, xAxisLabels, revTrendCatLabels, revTrendDataCat1, revTrendDataCat2, revTrendDataCat3, revTrendDataCat4, revTrendDataCat5, revTrendDataCat6, revTrendDataCat7, revTrendDataCat8, revTrendDataCat9, revTrendDataCat10);


    let revdataregular = [<?php for($i=0;$i<sizeof($revenueCatsFixed);$i++) echo "{$revenueCatsFixed[$i]['regular']}, "; ?>];
    let revdatafixed = [<?php  for($i=0;$i<sizeof($revenueCatsFixed);$i++) echo "{$revenueCatsFixed[$i]['fixed']}, "; ?>];
    chartGenerator.generateBarChart('revBarChart', yScale, ['fixed revenues', 'regular revenues', undefined, undefined],'Summed up revenue categories', revColors, expColors, revLabelArray, revdataregular, revdatafixed, 0, 0);
    // ++++++++++ EXPENDITURE-AREA ++++++++++ \\
    let expLabelArray = [<?php foreach($expendituresByCatC AS $key => $value) echo "'$key'" . ", "; ?>];
    let expDataArrayCurrency = [<?php foreach($expendituresByCatC AS $key => $value) echo "$value" . ", "; ?>];
    let expDataArrayPercentages = [<?php foreach($expendituresByCatP AS $key => $value) echo "$value" . ", "; ?>];
    chartGenerator.generateDoughnutChart('expDoughnut', expLabelArray, expDataArrayCurrency, expDataArrayPercentages, expColors, 'left', 'Summed up expenditures by categories', true);
    
    let expDataArrayAvgCurrency = [<?php foreach($expendituresByCatMonthlyAverageC AS $key => $value) echo "$value" . ", "; ?>];
    let expDataArrayAvgPercentages = [<?php foreach($expendituresByCatMonthlyAverageP AS $key => $value) echo "$value" . ", "; ?>];
    chartGenerator.generateDoughnutChart('expDoughnutAverage', expLabelArray, expDataArrayAvgCurrency, expDataArrayAvgPercentages, expColors, 'right', 'Average monthly expenditures by categories', true);

    let expTrendCatLabels = [<?php for($x=0; $x<sizeof($expendituresTrendByCatC); $x++) echo "'{$expendituresTrendByCatC[$x][0]}', "; ?>];
    let expTrendDataCat1 = [<?php if(isset($expendituresTrendByCatC[0][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "{$expendituresTrendByCatC[0][$x]}, "; ?>];
    let expTrendDataCat2 = [<?php if(isset($expendituresTrendByCatC[1][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$expendituresTrendByCatC[1][$x]}', "; ?>];
    let expTrendDataCat3 = [<?php if(isset($expendituresTrendByCatC[2][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$expendituresTrendByCatC[2][$x]}', "; ?>];
    let expTrendDataCat4 = [<?php if(isset($expendituresTrendByCatC[3][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$expendituresTrendByCatC[3][$x]}', "; ?>];
    let expTrendDataCat5 = [<?php if(isset($expendituresTrendByCatC[4][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$expendituresTrendByCatC[4][$x]}', "; ?>];
    let expTrendDataCat6 = [<?php if(isset($expendituresTrendByCatC[5][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$expendituresTrendByCatC[5][$x]}', "; ?>];
    let expTrendDataCat7 = [<?php if(isset($expendituresTrendByCatC[6][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$expendituresTrendByCatC[6][$x]}', "; ?>];
    let expTrendDataCat8 = [<?php if(isset($expendituresTrendByCatC[7][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$expendituresTrendByCatC[7][$x]}', "; ?>];
    let expTrendDataCat9 = [<?php if(isset($expendituresTrendByCatC[8][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$expendituresTrendByCatC[8][$x]}', "; ?>];
    let expTrendDataCat10 = [<?php if(isset($expendituresTrendByCatC[9][0])) for($x=1; $x<sizeof($dateArray)+1; $x++) echo "'{$expendituresTrendByCatC[9][$x]}', "; ?>];
    chartGenerator.generateLineChart('expTrendChart', 'Cumulative trend of expenditures', expColorsTransparent, 0, xAxisLabels, expTrendCatLabels, expTrendDataCat1, expTrendDataCat2, expTrendDataCat3, expTrendDataCat4, expTrendDataCat5, expTrendDataCat6, expTrendDataCat7, expTrendDataCat8, expTrendDataCat9, expTrendDataCat10);


    
    let expdataregular = [<?php for($i=0;$i<sizeof($expenditureCatsFixed);$i++) echo "{$expenditureCatsFixed[$i]['regular']}, "; ?>];
    let expdatafixed = [<?php  for($i=0;$i<sizeof($expenditureCatsFixed);$i++) echo "{$expenditureCatsFixed[$i]['fixed']}, "; ?>];
    if (yScale === 'logarithmic') {
        expdataregular = expdataregular.map(Math.abs);
        expdatafixed = expdatafixed.map(Math.abs);
    }
    chartGenerator.generateBarChart('expBarChart', yScale,  [undefined, undefined, 'fixed expenditures', 'regular expenditures'], 'Summed up expenditure categories', revColors, expColors, expLabelArray, 0, 0, expdataregular, expdatafixed);
</script>