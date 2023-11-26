<section class="tools-container">
    <div class="tool-box" id="whenMillionaireBox">
        <h2>When Millionaire?!</h2>
        <input type='image' src='./img/stonks.png' alt='Arrow symbol' height='236px' width='318px' id="stonks">
    </div>
    <div class="tool-box" id="cashflowPlannerBox">
        <h2>Cashflow Planner</h2>
        <input type='image' src='./img/moneybag.png' alt='Arrow symbol' height='320px' width='235px' id="moneybag">
    </div>
    <div class="tool-box" id="compundInterestCalculatorBox">
        <h2>Compund Interest Calculator</h2>
        <input type='image' src='./img/interest.png' alt='Arrow symbol' height='160px' width='320px' id="interest">
    </div>
    <!-- ========== WHEN MILLIONAIRE ========== -->
    <div class="tool-content hidden" id="whenMillionaireContent">
        <h3>When Millionaire?!</h3>
        <div class="charts-container-row">
            <span>Set assumption for wealth growth:</span>
        </div>
        <div class="settings-row">
            <form action="./?route=tools" method="post" class="chartScopeForm">
                <input type="hidden" name="millionaireAssumption" value="linear">
                <input type="submit" value="linear" 
                class="<?php if($millionaireAssumption === 'linear') echo 'chosen'; ?>">
            </form>
            <form action="./?route=tools" method="post" class="chartScopeForm">
                <input type="hidden" name="millionaireAssumption" value="exponentially">
                <input type="submit" value="exp" 
                class="<?php if($millionaireAssumption === 'exponentially') echo 'chosen'; ?>">
            </form>
        </div>
        <?php if($millionaireYears === 0): ?>
            <div class="charts-container-row">
                <span>You will never be a millionaire with decreasing total wealth!</span>
            </div>
        <?php else: ?>
            <div class="charts-container-row">
                <span>Congratulations, just stay on your path and you will be a millionaire in <?php echo $millionaireYears-1; ?> years!</span>
            </div>
            <?php for($i=0; $i<sizeof($millinaireTotalBalances); $i=$i+3): ?>
                    <div class="charts-container-row">
                    <?php if(isset(array_keys($millinaireTotalBalances)[$i])): ?>
                        <div class="report neutral">
                            <span class="report-label">
                                <?php echo e(array_keys($millinaireTotalBalances)[$i]); ?>
                            </span>
                            <span class="report-content">
                                <?php echo number_format(array_values($millinaireTotalBalances)[$i], 0, ",", ".") . " €"; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <?php if(isset(array_keys($millinaireTotalBalances)[$i+1])): ?>
                        <div class="report neutral">
                            <span class="report-label">
                                <?php echo e(array_keys($millinaireTotalBalances)[$i+1]); ?>
                            </span>
                            <span class="report-content">
                                <?php echo number_format(array_values($millinaireTotalBalances)[$i+1], 0, ",", ".") . " €"; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <?php if(isset(array_keys($millinaireTotalBalances)[$i+2])): ?>
                        <div class="report neutral">
                            <span class="report-label">
                                <?php echo e(array_keys($millinaireTotalBalances)[$i+2]); ?>
                            </span>
                            <span class="report-content">
                                <?php echo number_format(array_values($millinaireTotalBalances)[$i+2], 0, ",", ".") . " €"; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
            <?php if($millionaireYears < 1000) : ?>
                <div class="charts-container-row">
                    <canvas id="millionaireLinechart" width="1000px" height="500px"></canvas>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <input type="button" value="Get Back" class="cancel-btn" id="getBackMillionaire">
    </div>
    <?php if($millionaireTrend !== null): ?>
        <script type="module">
        "use strict";
        import ChartGenerator from "./src/JS/Classes/ChartGenerator.js";
        let chartGenerator = new ChartGenerator();

        let backgroundColorTransp10 = [<?php foreach($chartColors[0] AS $color) echo "'$color', "; ?>]
        let millionaireYLabels = [<?php for($i=0; $i<$millionaireYears+1; $i++) echo "'" . (int) date('Y') + $i . "', "; ?>];
        let millionaireYCategoryLabels = [<?php for($x=0; $x<sizeof($millionaireTrend[0]); $x++) echo "'" . $millionaireTrend[0][$x] . "', "; ?>];
        let millionaireGoalData = [<?php for($x=1; $x<$millionaireYears+1; $x++) echo "1000000, "; ?>];

        <?php foreach($millionaireTrend AS $arry) array_pop($arry); ?>
        let millionaireYDataCat1 = [<?php if(isset($millionaireTrend[1][0])) for($x=1; $x<$millionaireYears+1; $x++) echo "{$millionaireTrend[$x][0]}, "; ?>];
        let millionaireYDataCat2 = [<?php if(isset($millionaireTrend[1][1])) for($x=1; $x<$millionaireYears+1; $x++) echo "{$millionaireTrend[$x][1]}, "; ?>];
        let millionaireYDataCat3 = [<?php if(isset($millionaireTrend[1][2])) for($x=1; $x<$millionaireYears+1; $x++) echo "{$millionaireTrend[$x][2]}, "; ?>];
        let millionaireYDataCat4 = [<?php if(isset($millionaireTrend[1][3])) for($x=1; $x<$millionaireYears+1; $x++) echo "{$millionaireTrend[$x][3]}, "; ?>];
        let millionaireYDataCat5 = [<?php if(isset($millionaireTrend[1][4])) for($x=1; $x<$millionaireYears+1; $x++) echo "{$millionaireTrend[$x][4]}, "; ?>];
        let millionaireYDataCat6 = [<?php if(isset($millionaireTrend[1][5])) for($x=1; $x<$millionaireYears+1; $x++) echo "{$millionaireTrend[$x][5]}, "; ?>];
        let millionaireYDataCat7 = [<?php if(isset($millionaireTrend[1][6])) for($x=1; $x<$millionaireYears+1; $x++) echo "{$millionaireTrend[$x][6]}, "; ?>];
        let millionaireYDataCat8 = [<?php if(isset($millionaireTrend[1][7])) for($x=1; $x<$millionaireYears+1; $x++) echo "{$millionaireTrend[$x][7]}, "; ?>];
        let millionaireYDataCat9 = [<?php if(isset($millionaireTrend[1][8])) for($x=1; $x<$millionaireYears+1; $x++) echo "{$millionaireTrend[$x][8]}, "; ?>];
        let millionaireYDataCat10 = [<?php if(isset($millionaireTrend[1][9])) for($x=1; $x<$millionaireYears+1; $x++) echo "{$millionaireTrend[$x][9]}, "; ?>];
        chartGenerator.generateLineChart('millionaireLinechart', 'Cumulative trend of wealth distributions', backgroundColorTransp10, millionaireGoalData, millionaireYLabels, millionaireYCategoryLabels, millionaireYDataCat1, millionaireYDataCat2, millionaireYDataCat3, millionaireYDataCat4, millionaireYDataCat5, millionaireYDataCat6, millionaireYDataCat7, millionaireYDataCat8, millionaireYDataCat9, millionaireYDataCat10);
        </script>
    <?php endif; ?>
    <!-- ========== CASHFLOW PLANNER ========== -->
    <div class="tool-content hidden" id="cashflowPlannerContent">
        <form action="./?route=tools" method="POST">
            <h3>Cashflow Planner</h3>
            <div class="planner-row">
                <span class="planner-span revenue">Revenues</span>
                <span class="planner-span expenditure">Expenditures</span>
            </div>
            <?php if($cashFlowPlanner === null): ?>
                <?php for($i=1; $i<11; $i++): ?>
                    <div class="planner-row">
                        <input type="text" minlength="3" maxlength="50" name="revName<?php echo $i; ?>" class="planner-span revenue" placeholder="Revenue Category <?php echo $i; ?>">
                        <input type="number" min="0" step="1" max="100000000" name="revAmount<?php echo $i; ?>" class="planner-span revenue">
                        <input type="text" minlength="3" maxlength="50" name="expName<?php echo $i; ?>" class="planner-span expenditure" placeholder="Expenditure Category <?php echo $i; ?>">
                        <input type="number" min="0" step="1" max="100000000" name="expAmount<?php echo $i; ?>" class="planner-span expenditure">
                    </div>
                <?php endfor; ?>
            <?php else: ?>
                <?php for($i=1; $i<11; $i++): ?>
                    <div class="planner-row">
                        <input type="text" maxlength="50" name="revName<?php echo $i; ?>" class="planner-span revenue" placeholder="Revenue Category <?php echo $i; ?>" value="<?php echo e($cashFlowPlanner["revName{$i}"]); ?>">
                        <input type="number" min="0" step="1" max="100000000" name="revAmount<?php echo $i; ?>" class="planner-span revenue" value="<?php echo e($cashFlowPlanner["revAmount{$i}"]); ?>">
                        <input type="text" maxlength="50" name="expName<?php echo $i; ?>" class="planner-span expenditure" placeholder="Expenditure Category <?php echo $i; ?>" value="<?php echo e($cashFlowPlanner["expName{$i}"]); ?>">
                        <input type="number" min="0" step="1" max="100000000" name="expAmount<?php echo $i; ?>" class="planner-span expenditure" value="<?php echo e($cashFlowPlanner["expAmount{$i}"]); ?>">
                    </div>
                <?php endfor; ?>
                <div class="planner-row">
                    <span class="planner-span revenue" style="font-weight: bold;">Total Revenues</span>
                    <span class="planner-span revenue" style="font-weight: bold;"><?php echo number_format($balances[0], 0, ',', '.') . "€"; ?></span>
                    <span class="planner-span expenditure" style="font-weight: bold;">Total Expenditures</span>
                    <span class="planner-span expenditure" style="font-weight: bold;"><?php echo number_format($balances[1], 0, ',', '.') . "€"; ?></span>
                </div>
                <div class="planner-row" id="planner-main-balance-row">
                    <span class="planner-span <?php if($balances[2] >= 0) echo "revenue"; else echo "expenditure"; ?>">Total Cashflow</span>
                    <span class="planner-span <?php if($balances[2] >= 0) echo "revenue"; else echo "expenditure"; ?>"><?php echo number_format($balances[2], 0, ',', '.') . "€"; ?></span>
                </div>
            <?php endif; ?>
                
                <div class="planner-row">
                    <input type="submit" value="Calculate" class="btn" style="margin-left: auto;">
                    <input type="button" value="Get Back" class="cancel-btn" id="getBackPlanner" style="margin-right: auto;">
                </div>
        </form>
    </div>
    <!-- ========== COMPOUND INTEREST CALCULATOR ========== -->
    <div class="tool-content hidden" id="compundInterestCalculatorContent">
        <h3>Compund Interest Calculator</h3>
        <form action="./?route=tools" method="POST" class="chartForm">
            <div class="charts-container-row">
                <label for="initialCapital">Initial Capital [€]:</label>
                <input type="number" name="initialCapital" id="initialCapital" min="0" step="1" max="1000000" value="<?php echo $initialCapital; ?>" required>
            </div>
            <div class="charts-container-row">
                <label for="regularInvest">Yearly Invest [€]:</label>
                <input type="number" name="regularInvest" id="regularInvest" min="0" step="1" max="1000000" value="<?php echo $regularInvest; ?>" required>
            </div>
            <div class="charts-container-row">
                <label for="investDuration">Duration [years]:</label>
                <input type="number" name="investDuration" id="investDuration" min="0" step="1" max="100" value="<?php echo $investDuration; ?>" required>
            </div>
            <div class="charts-container-row">
                <label for="interestRate">APY [%]:</label>
                <input type="number" name="interestRate" id="interestRate" min="0.01" step="0.01" max="30" value="<?php echo $interestRate*100; ?>" required>
            </div>
            <div class="charts-container-row">
                <input type="submit" value="Calculate" class="btn">
                <input type="button" value="Get Back" class="cancel-btn" id="getBackInterest">
            </div>
        </form>
        <?php if(sizeof($compoundInterestArray) !== 0): ?>
            <div class="charts-container-row">
                <div class="report neutral">
                    <span class="report-label">
                        Total Invest
                    </span>
                    <span class="report-content">
                        <?php echo number_format(end($compoundInterestArray)[0], 0, ",", ".") . "€"; ?>
                    </span>
                </div>
                <div class="report neutral">
                    <span class="report-label">
                        Total Interest
                    </span>
                    <span class="report-content">
                        <?php echo number_format(end($compoundInterestArray)[1], 0, ",", ".") . "€"; ?>
                    </span>
                </div>
                <div class="report neutral">
                    <span class="report-label">
                        Total Balance
                    </span>
                    <span class="report-content">
                        <?php echo number_format(end($compoundInterestArray)[0]+end($compoundInterestArray)[1], 0, ",", ".") . "€"; ?>
                    </span>
                </div>
            </div>
            <div class="charts-container-row">
                <canvas id="compoundInterestBarchart" width="1000px" height="500px"></canvas>
            </div>
        <?php endif; ?>
    </div>
</section>   
<script type="module">
    "use strict";
    import ChartGenerator from "./src/JS/Classes/ChartGenerator.js";
    let chartGenerator = new ChartGenerator();
    
    let colors = [<?php foreach($chartColors[1] AS $color) echo "'${color}', "; ?>];
    let labelArray = [<?php foreach($yearsArray AS $year) echo "'$year', "; ?>];
    let invest = [<?php for($i=0;$i<sizeof($compoundInterestArray);$i++) echo "{$compoundInterestArray[$i][0]}, "; ?>];
    let interest = [<?php  for($i=0;$i<sizeof($compoundInterestArray);$i++) echo "{$compoundInterestArray[$i][1]}, "; ?>];

    chartGenerator.generateBarChart('compoundInterestBarchart', 'linear',  ['Invest', 'Interest', undefined, undefined], 'Compund Interest', colors, [0,0], labelArray, interest, invest, 0, 0);
</script>
<script defer>
    document.getElementById('whenMillionaireBox').addEventListener("click", e => {
        document.getElementById('whenMillionaireContent').classList.toggle("hidden");
        document.getElementById('whenMillionaireBox').classList.toggle("hidden");
        document.getElementById('cashflowPlannerBox').classList.toggle("hidden");
        document.getElementById('compundInterestCalculatorBox').classList.toggle("hidden");
        localStorage.setItem("tool", JSON.stringify('whenMillionaire'));
    });

    document.getElementById('getBackMillionaire').addEventListener("click", e => {
        e.preventDefault();
        document.getElementById('whenMillionaireContent').classList.toggle("hidden");
        document.getElementById('whenMillionaireBox').classList.toggle("hidden");
        document.getElementById('cashflowPlannerBox').classList.toggle("hidden");
        document.getElementById('compundInterestCalculatorBox').classList.toggle("hidden");
        localStorage.setItem("tool", JSON.stringify('start'));
    });

    document.getElementById('cashflowPlannerBox').addEventListener("click", e => {
        document.getElementById('cashflowPlannerContent').classList.toggle("hidden");
        document.getElementById('cashflowPlannerBox').classList.toggle("hidden");
        document.getElementById('whenMillionaireBox').classList.toggle("hidden");
        document.getElementById('compundInterestCalculatorBox').classList.toggle("hidden");
        localStorage.setItem("tool", JSON.stringify('cashFlowPlanner'));
    });

    document.getElementById('getBackPlanner').addEventListener("click", e => {
        e.preventDefault();
        document.getElementById('cashflowPlannerContent').classList.toggle("hidden");
        document.getElementById('cashflowPlannerBox').classList.toggle("hidden");
        document.getElementById('whenMillionaireBox').classList.toggle("hidden");
        document.getElementById('compundInterestCalculatorBox').classList.toggle("hidden");
        localStorage.setItem("tool", JSON.stringify('start'));
    });

    document.getElementById('compundInterestCalculatorBox').addEventListener("click", e => {
        document.getElementById('compundInterestCalculatorContent').classList.toggle("hidden");
        document.getElementById('compundInterestCalculatorBox').classList.toggle("hidden");
        document.getElementById('whenMillionaireBox').classList.toggle("hidden");
        document.getElementById('cashflowPlannerBox').classList.toggle("hidden");
        localStorage.setItem("tool", JSON.stringify('compundInterest'));
    });

    document.getElementById('getBackInterest').addEventListener("click", e => {
        e.preventDefault();
        document.getElementById('compundInterestCalculatorContent').classList.toggle("hidden");
        document.getElementById('compundInterestCalculatorBox').classList.toggle("hidden");
        document.getElementById('whenMillionaireBox').classList.toggle("hidden");
        document.getElementById('cashflowPlannerBox').classList.toggle("hidden");
        localStorage.setItem("tool", JSON.stringify('start'));
    });

    let usedTool = JSON.parse(localStorage.getItem("tool"));
    if(usedTool === 'whenMillionaire') {
        document.getElementById('whenMillionaireContent').classList.toggle("hidden");
        document.getElementById('whenMillionaireBox').classList.toggle("hidden");
        document.getElementById('cashflowPlannerBox').classList.toggle("hidden");
        document.getElementById('compundInterestCalculatorBox').classList.toggle("hidden");
    } else if(usedTool === 'cashFlowPlanner') {
        document.getElementById('cashflowPlannerContent').classList.toggle("hidden");
        document.getElementById('cashflowPlannerBox').classList.toggle("hidden");
        document.getElementById('whenMillionaireBox').classList.toggle("hidden");
        document.getElementById('compundInterestCalculatorBox').classList.toggle("hidden");
    } else if (usedTool === 'compundInterest') {
        document.getElementById('compundInterestCalculatorContent').classList.toggle("hidden");
        document.getElementById('compundInterestCalculatorBox').classList.toggle("hidden");
        document.getElementById('whenMillionaireBox').classList.toggle("hidden");
        document.getElementById('cashflowPlannerBox').classList.toggle("hidden");
    }
</script>