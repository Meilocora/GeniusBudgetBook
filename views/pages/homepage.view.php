<div class="scroll-container">
    <div class="scroll-element">
        <a href="#header-banner-name"><img src="./img/arrow_up.png" alt="Arrow symbol that points upwards" height='20px' width='20px'></a>    
        <a href="#wdContainer" class="scroll1">Total Wealth</a>
        <a href="#donationsContainer" class="scroll2">Donation Goal</a>
        <a href="#savingsContainer" class="scroll3">Saving Goal</a>
        <a href="#footer"><img src="./img/arrow_down.png" alt="Arrow symbol that points downwards" height='20px' width='20px'></a>
    </div>
</div>
<div class="adjustments-container">
    <img src="./img/gear.png" alt="Symbol of a gear" height='40px' width='40px' class="adjustmentsSymbol">
    <div class="adjustments-box" id="homepageBox">
        <div class="settings-row">
            <span>Choose a year:</span>
        </div>
        <div class="settings-row">
            <form action="./?route=homepage" method="post">
                <input type="number" name="year" min="1970" max="<?php echo date('Y'); ?>" value="<?php echo $year; ?>">
                <input type='image' src='./img/checkmark.png' alt='Checkmark symbol' height='20px' width='20px'>
            </form> 
        </div>
        <div class="settings-row">
            <span>Change charts colors:</span>
        </div>
        <div class="settings-row">
            <form action="./?route=homepage" method="post" class="chartScopeForm">
                <input type="hidden" name="chartColorSet" value="default">
                <input type="submit" value="default" 
                class="<?php if($chartColorSet === 'default') echo 'chosen'; ?>">
            </form>
            <form action="./?route=homepage" method="post" class="chartScopeForm">
                <input type="hidden" name="chartColorSet" value="colorful">
                <input type="submit" value="colorful" 
                class="<?php if($chartColorSet === 'colorful') echo 'chosen'; ?>">
            </form>
            <?php if(isset($_COOKIE['customChartColorTheme'])): ?>
            <form action="./?route=homepage" method="post" class="chartScopeForm">
                <input type="hidden" name="chartColorSet" value="custom">
                <input type="submit" value="custom"
                class="<?php if($chartColorSet === 'custom') echo 'chosen'; ?>">
            </form>
            <?php endif; ?>
        </div>
        <div class="settings-row">
            <span>Change timeinterval:</span>
        </div>
        <div class="settings-row">
            <form action="./?route=homepage" method="post" class="chartScopeForm">
                <input type="hidden" name="timeInterval" value="YTD">
                <input type="submit" value="YTD" 
                class="<?php if($timeInterval === 'YTD') echo 'chosen'; ?>">
            </form>
            <form action="./?route=homepage" method="post" class="chartScopeForm">
                <input type="hidden" name="timeInterval" value="YOY">
                <input type="submit" value="YoY" 
                class="<?php if($timeInterval === 'YOY') echo 'chosen'; ?>">
            </form>
            <form action="./?route=homepage" method="post" class="chartScopeForm">
                <input type="hidden" name="timeInterval" value="ALL">
                <input type="submit" value="All" 
                class="<?php if($timeInterval === 'ALL') echo 'chosen'; ?>">
            </form>
        </div>
        <div class="customDatesBox">
            <div class="settings-row">
                <form action="./?route=homepage" method="post" class="chartScopeForm">
                    <span>Custom date</span>
            </div>
            <div class="settings-row">
                    <input type="hidden" name="timeInterval" value="Custom">
                    <input type="month" name="customStartMonth" max="<?php echo date('Y-m', strtotime("-1 month")); ?>" value="<?php echo date('Y-m', strtotime($startDate)); ?>" required>
                    <span>-</span>
                    <input type="month" name="customEndMonth" max="<?php echo date('Y-m'); ?>" value="<?php echo $queryDate; ?>" required>
                    <input type='image' src='./img/checkmark.png' alt='Checkmark symbol' height='30px' width='30px'>
                </form>
            </div>
        </div>
        <div class="settings-row">
            <span>Adjust goals:</span>
        </div>
        <div class="settings-row">
            <form action="./?route=homepage/adjustgoals" method="post">
                <label for="totalwealthgoal">Total wealth goal: </label>
                <input type="number" min="0" step="1" value="<?php echo e($goalsArray['totalwealthgoal']); ?>" name="totalwealthgoal" id="totalwealthgoal" class="change-goals">
        </div>
        <div class="settings-row">
                <label for="donationgoal">Donation goal: </label>
                <input type="number" min="0" step="1" value="<?php echo e($goalsArray['donationgoal']); ?>" name="donationgoal" id="donationgoal" class="change-goals">
        </div>
        <div class="settings-row">
                <label for="savinggoal">Saving goal: </label>
                <input type="number" min="0" step="1" value="<?php echo e($goalsArray['savinggoal']); ?>" name="savinggoal" id="savinggoal" class="change-goals">
        </div>
        <div class="settings-row">
                <input type="hidden" name="year" value="<?php echo $year; ?>">
                <input type='image' src='./img/checkmark.png' alt='Checkmark symbol' height='20px' width='20px'>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="./src/JS/jQuery/jQuery.js" defer></script>
<script type="text/javascript" src="./src/JS/jQuery/jquery.waypoints.min.js" defer></script>
<script type="module" defer>
    'use strict';
    import "./src/JS/jQuery/jquery.waypoints.min.js";
    let scroll1 = document.querySelector('.scroll1');
    let scroll2 = document.querySelector('.scroll2');
    let scroll3 = document.querySelector('.scroll3');
    let waypoint1 = new Waypoint({
            element: document.getElementById('wdContainer'),
            handler: function(direction) {
                scroll1.setAttribute("class", "scrollActive");
                scroll2.removeAttribute("class");
                scroll3.removeAttribute("class");
            }
    });
    let waypoint2 = new Waypoint({
            element: document.getElementById('donationsContainer'),
            handler: function(direction) {
                scroll1.removeAttribute("class");
                scroll2.setAttribute("class", "scrollActive");
                scroll3.removeAttribute("class");
            }
    });
    let waypoint3 = new Waypoint({
            element: document.getElementById('savingsContainer'),
            handler: function(direction) {
                scroll1.removeAttribute("class");
                scroll2.removeAttribute("class");
                scroll3.setAttribute("class", "scrollActive");
            }
    });
</script>
<section class="chart-view-container">
    <div class="charts-container" id="wdContainer">
        <div class="charts-container-row">
            <h1>Total Wealth</h1>
        </div>
        <?php if($currentGoalSharesC['Missing wealth'] !== 0 & $year === date('Y') & $donationsArrayC[1] !== 0): ?>
            <div class="charts-container-row">
                <span>Your total assets are currently worth: <?php echo number_format($currentTotalWealth, '0', ',', '.') . ' €'; ?></span>
            </div>
            <div class="charts-container-row">
                <span>You have <?php echo e($daysleft); ?> days to reach your personal wealth goal of <?php echo number_format($goalsArray["totalwealthgoal"], '0', ',', '.') . '€'; ?>...</span>
            </div> 
            <div class="charts-container-row">
                <span>Just accumulate <?php echo number_format(($currentGoalSharesC['Missing wealth']/$daysleft), '0', ',', '.') . '€'; ?> each day und you will be there!</span>
            </div>
        <?php elseif($currentGoalSharesC['Missing wealth'] === 0 & $year === date('Y') & $donationsArrayC[1] !== 0): ?>
            <div class="charts-container-row">
                <span>Congratulations, you have reached your personal wealth goal of <?php echo number_format($goalsArray["totalwealthgoal"], '0', ',', '.') . '€'; ?> already!!!</span>
            </div> 
            <div class="charts-container-row">
                <span>Make sure to reach your donation goal aswell...</span>
            </div>
        <?php elseif($currentGoalSharesC['Missing wealth'] === 0 & $year === date('Y') & $donationsArrayC[1] === 0): ?>
        <div class="charts-container-row">
            <span>Congratulations, you have reached your personal wealth goal of <?php echo number_format($goalsArray["totalwealthgoal"], '0', ',', '.') . '€'; ?> already!!!</span>
        </div> 
        <div class="charts-container-row">
            <span>Since you also achieved your donation goal it's party time for the rest of the year!!!</span>
        </div>
        <?php endif; ?>    
        <div class="charts-container-row">
            <?php if(!empty($currentWDActualArrayC) & !empty($currentWDTargetArrayC) & array_values($currentGoalSharesC) !== [0, 0]): ?>
                <canvas id="wdDoughnutActual" width="600px" height="400px"></canvas>
                <canvas id="wdDoughnutGoal" width="600px" height="400px"></canvas>
            <?php else: ?>
                <h2>No financial data for <?php echo $year; ?> available.</h2>
            <?php endif; ?>
        </div>  
        <?php if($wdYC !== [[0], [0]]): ?>
            <div class="charts-container-row">
                <canvas id="wdTrendChartActualC" width="1100px" height="450px"></canvas>
            </div>
            <div class="charts-container-row">
                <canvas id="wdTrendChartActualTargetC" width="1100px" height="450px"></canvas>
            </div>  
        <?php endif; ?>
    </div>

    <!-- ++++++++++ DONATIONS-AREA ++++++++++ -->
    <div class="charts-container" id="donationsContainer">
        <div class="charts-container-row">
            <h1>Donation Goal</h1>
        </div>
        <?php if($goalsArray['donationgoal'] !== 0): ?>
            <div class="charts-container-row">
                <span>Your donation goal is <?php echo number_format(array_sum($donationsArrayC), '0', ',', '.') . '€.'; ?></span>
            </div>
            <div class="charts-container-row">
                <?php if($donationsArrayC[1] !== 0): ?>
                    <span>You need to donate <?php echo number_format($donationsArrayC[1], '0', ',', '.') . '€ '; ?> more to reach the goal!</span>
                <?php else: ?>
                    <span>Congratulations! You already reached your donations goal.</span>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="charts-container-row">
                <h2>No monetary goals set for <?php echo $year; ?>.</h2>
            </div>
        <?php endif; ?>
        <?php if($goalsArray['donationgoal'] !== 0): ?>
            <div class="charts-container-row">
                <canvas id="donationsDoughnut" width="600px" height="400px"></canvas>
                <?php if(!empty($donationEntries)): ?>
                    <table id="homepage-donationEntries">
                        <caption>List of all donation entries of <?php echo $year; ?></caption>
                        <thead>
                            <tr>
                                <td>Category</td>
                                <td>Title</td>
                                <td>Amount</td>
                                <td>Date</td>
                                <td>Comment</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($donationEntries AS $entry): ?>
                                <tr>
                                    <td><?php echo e($entry->category); ?></td>
                                    <td><?php echo e($entry->title); ?></td>
                                    <td><?php echo number_format(e($entry->amount), '0', ',', '.') . ' €'; ?></td>
                                    <td><?php echo e($entry->dateslug); ?></td>
                                    <td><?php echo e($entry->comment); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- ++++++++++ SAVINGS-AREA ++++++++++ -->
    <div class="charts-container" id="savingsContainer">
        <div class="charts-container-row">
            <h1>Saving Goal</h1>
        </div>
        <?php if($goalsArray['savinggoal'] !== 0): ?>
            <div class="charts-container-row">
                <span>Your total liquid assets are currently worth: <?php echo number_format($currentTotalLiquid, '0', ',', '.') . ' €'; ?></span>
            </div>
            <div class="charts-container-row">
                <span>Your saving goal for <?php echo $year; ?> is <?php echo $goalsArray['savinggoal']; ?> €...</span>
            </div>
            <div class="charts-container-row">
                <span>You need to save <?php echo number_format($currentSavingGoalSharesC['Missing liquid wealth'], '0', ',', '.') . '€ '; ?> more to reach the goal!</span>
            </div>
            <div class="charts-container-row">
                <canvas id="savingsDoughnutActual" width="600px" height="400px"></canvas>
                <canvas id="savingsDoughnutGoal" width="600px" height="400px"></canvas>
            </div>
            <div class="charts-container-row">
                    <canvas id="savingsTrendChartActualC" width="1100px" height="450px"></canvas>
            </div>
        <?php else: ?>
            <div class="charts-container-row">
                <h2>No monetary goals set for <?php echo $year; ?>.</h2>
            </div>
        <?php endif; ?>
    </div>  
</section>

<script type="module">
    "use strict";
    import ChartGenerator from "./src/JS/Classes/ChartGenerator.js";
    let chartGenerator = new ChartGenerator();
    let backgroundColor10 = [<?php foreach($backgroundColor10 AS $color) echo "'$color', "; ?>]
    let backgroundColor2 = [<?php foreach($backgroundColor2 AS $color) echo "'$color', "; ?>]
    let backgroundColorTransp10 = [<?php foreach($backgroundColorTransp10 AS $color) echo "'$color', "; ?>]
    let backgroundColorTransp2 = [<?php foreach($backgroundColorTransp2 AS $color) echo "'$color', "; ?>]

    let wdLabelArray = [<?php foreach($currentWDActualArrayC AS $key => $value) echo "'$key'" . ", "; ?>];
    let wdDataArrayCurrency = [<?php foreach($currentWDActualArrayC AS $key => $value) echo "$value" . ", "; ?>];
    let wdDataArrayPercentages = [<?php foreach($currentWDActualArrayP AS $key => $value) echo "$value" . ", "; ?>];
    let wGoalLabelArray = [<?php foreach($currentGoalSharesC AS $key => $value) echo "'$key'" . ", "; ?>];
    let wGoalDataArrayCurrency = [<?php foreach($currentGoalSharesC AS $data) echo "'$data', "; ?>];
    let wGoalDataArrayPercentages = [<?php foreach($currentGoalSharesP AS $data) echo "'$data', "; ?>];
    chartGenerator.generateDoughnutChart('wdDoughnutActual', wdLabelArray, wdDataArrayCurrency, wdDataArrayPercentages, backgroundColor10, 'left', 'Most current wealth distributions of <?php echo $year; ?>', true);
    chartGenerator.generateDoughnutChart('wdDoughnutGoal', wGoalLabelArray, wGoalDataArrayCurrency, wGoalDataArrayPercentages, backgroundColor2, 'right', 'Most current total wealth goal data of <?php echo $year; ?>', true);

    let wdTrendYLabels = [<?php foreach(end($wdYC) AS $date) echo "'$date', "; ?>];
    let wdTrendYCategoryLabels = [<?php for($x=0; $x<sizeof($wdYC)-1; $x++) echo "'{$wdYC[$x][0]}', "; ?>];
    let wdGoalData = [<?php for($x=1; $x<sizeof($wdYC[0]); $x++) echo "{$goalsArray["totalwealthgoal"]}, "; ?>];

    <?php if(array_sum($wdYC[0]) + array_sum($wdYC[1]) !== 0): ?>
        let wdTrendYDataCat1 = [<?php if(isset($wdYC[1][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[0][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "{$wdYC[0][$x]}, "; ?>];
        let wdTrendYDataCat2 = [<?php if(isset($wdYC[1][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[1][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[1][$x]}', "; ?>];
        let wdTrendYDataCat3 = [<?php if(isset($wdYC[2][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[2][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[2][$x]}', "; ?>];
        let wdTrendYDataCat4 = [<?php if(isset($wdYC[3][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[3][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[3][$x]}', "; ?>];
        let wdTrendYDataCat5 = [<?php if(isset($wdYC[4][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[4][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[4][$x]}', "; ?>];
        let wdTrendYDataCat6 = [<?php if(isset($wdYC[5][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[5][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[5][$x]}', "; ?>];
        let wdTrendYDataCat7 = [<?php if(isset($wdYC[6][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[6][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[6][$x]}', "; ?>];
        let wdTrendYDataCat8 = [<?php if(isset($wdYC[7][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[7][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[7][$x]}', "; ?>];
        let wdTrendYDataCat9 = [<?php if(isset($wdYC[8][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[8][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[8][$x]}', "; ?>];
        let wdTrendYDataCat10 = [<?php if(isset($wdYC[9][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[9][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[9][$x]}', "; ?>];
        chartGenerator.generateLineChart('wdTrendChartActualC', 'Cumulative trend of wealth distributions', backgroundColorTransp10, wdGoalData, wdTrendYLabels, wdTrendYCategoryLabels, wdTrendYDataCat1, wdTrendYDataCat2, wdTrendYDataCat3, wdTrendYDataCat4, wdTrendYDataCat5, wdTrendYDataCat6, wdTrendYDataCat7, wdTrendYDataCat8, wdTrendYDataCat9, wdTrendYDataCat10);
    <?php endif; ?>

    let wdTrendYTALabels = [<?php for($x=0; $x<sizeof($wdYTargetActualC)-1; $x++) echo "'{$wdYTargetActualC[$x][0]}', "; ?>];
    let wdTrendYTData = [<?php for($x=1; $x<sizeof($wdYTargetActualC[0]); $x++) echo "{$wdYTargetActualC[0][$x]}, "; ?>];
    let wdTrendYAData = [<?php for($x=1; $x<sizeof($wdYTargetActualC[0]); $x++) echo "{$wdYTargetActualC[1][$x]}, "; ?>];
    chartGenerator.generateLineChart('wdTrendChartActualTargetC', 'Comparison of target and actual trend', backgroundColorTransp2, wdGoalData, wdTrendYLabels, wdTrendYTALabels.reverse(), wdTrendYAData, wdTrendYTData);
    // ++++++++++ DONATIONS-AREA ++++++++++
    let donationsGoalDataCurrency = [<?php echo "$donationsArrayC[0], $donationsArrayC[1]"; ?>];
    let donationsGoalDataPercentages = [<?php echo "$donationsArrayP[0], $donationsArrayP[1]"; ?>];
    chartGenerator.generateDoughnutChart('donationsDoughnut', ['Donations made', 'Donations missing'], donationsGoalDataCurrency, donationsGoalDataPercentages, backgroundColor2, 'bottom', '', false);
    // ++++++++++ SAVINGS-AREA ++++++++++
    let lLabelArray = [<?php foreach($currentSavingsActualArrayC AS $key => $value) echo "'$key'" . ", "; ?>];
    let lDataArrayCurrency = [<?php foreach($currentSavingsActualArrayC AS $key => $value) echo "$value" . ", "; ?>];
    let lDataArrayPercentages = [<?php foreach($currentSavingsActualArrayP AS $key => $value) echo "$value" . ", "; ?>];
    chartGenerator.generateDoughnutChart('savingsDoughnutActual', lLabelArray, lDataArrayCurrency, lDataArrayPercentages, backgroundColor10, 'left', 'Most current savings of <?php echo $year; ?>', true);

    let lGoalLabelArray = [<?php foreach($currentSavingGoalSharesC AS $key => $value) echo "'$key'" . ", "; ?>];
    let lGoalDataArrayCurrency = [<?php foreach($currentSavingGoalSharesC AS $data) echo "'$data', "; ?>];
    let lGoalDataArrayPercentages = [<?php foreach($currentSavingGoalSharesP AS $data) echo "'$data', "; ?>];
    chartGenerator.generateDoughnutChart('savingsDoughnutGoal', lGoalLabelArray, lGoalDataArrayCurrency, lGoalDataArrayPercentages, backgroundColor2, 'right', 'Most current saving goal data of <?php echo $year; ?>', true);

    <?php if(array_sum($savingsArrayC[0]) + array_sum($savingsArrayC[1]) !== 0): ?>
        let savingsTrendYLabels = [<?php foreach(end($savingsArrayC) AS $date) echo "'$date', "; ?>];
        let savingsTrendYCategoryLabels = [<?php for($x=0; $x<sizeof($savingsArrayC)-1; $x++) echo "'{$savingsArrayC[$x][0]}', "; ?>];
        let savingsGoalData = [<?php for($x=1; $x<sizeof($savingsArrayC[0]); $x++) echo "{$goalsArray["savinggoal"]}, "; ?>];
        let savingsTrendYDataCat1 = [<?php if(isset($savingsArrayC[1][0]) && !preg_match('/^(.*\s+.*)+$/', $savingsArrayC[0][1])) for($x=1; $x<sizeof($savingsArrayC[0]); $x++) echo "{$savingsArrayC[0][$x]}, "; ?>];
        let savingsTrendYDataCat2 = [<?php if(isset($savingsArrayC[1][0]) && !preg_match('/^(.*\s+.*)+$/', $savingsArrayC[1][1])) for($x=1; $x<sizeof($savingsArrayC[0]); $x++) echo "'{$savingsArrayC[1][$x]}', "; ?>];
        let savingsTrendYDataCat3 = [<?php if(isset($savingsArrayC[2][0]) && !preg_match('/^(.*\s+.*)+$/', $savingsArrayC[2][1])) for($x=1; $x<sizeof($savingsArrayC[0]); $x++) echo "'{$savingsArrayC[2][$x]}', "; ?>];
        let savingsTrendYDataCat4 = [<?php if(isset($savingsArrayC[3][0]) && !preg_match('/^(.*\s+.*)+$/', $savingsArrayC[3][1])) for($x=1; $x<sizeof($savingsArrayC[0]); $x++) echo "'{$savingsArrayC[3][$x]}', "; ?>];
        let savingsTrendYDataCat5 = [<?php if(isset($savingsArrayC[4][0]) && !preg_match('/^(.*\s+.*)+$/', $savingsArrayC[4][1])) for($x=1; $x<sizeof($savingsArrayC[0]); $x++) echo "'{$savingsArrayC[4][$x]}', "; ?>];
        let savingsTrendYDataCat6 = [<?php if(isset($savingsArrayC[5][0]) && !preg_match('/^(.*\s+.*)+$/', $savingsArrayC[5][1])) for($x=1; $x<sizeof($savingsArrayC[0]); $x++) echo "'{$savingsArrayC[5][$x]}', "; ?>];
        let savingsTrendYDataCat7 = [<?php if(isset($savingsArrayC[6][0]) && !preg_match('/^(.*\s+.*)+$/', $savingsArrayC[6][1])) for($x=1; $x<sizeof($savingsArrayC[0]); $x++) echo "'{$savingsArrayC[6][$x]}', "; ?>];
        let savingsTrendYDataCat8 = [<?php if(isset($savingsArrayC[7][0]) && !preg_match('/^(.*\s+.*)+$/', $savingsArrayC[7][1])) for($x=1; $x<sizeof($savingsArrayC[0]); $x++) echo "'{$savingsArrayC[7][$x]}', "; ?>];
        let savingsTrendYDataCat9 = [<?php if(isset($savingsArrayC[8][0]) && !preg_match('/^(.*\s+.*)+$/', $savingsArrayC[8][1])) for($x=1; $x<sizeof($savingsArrayC[0]); $x++) echo "'{$savingsArrayC[8][$x]}', "; ?>];
        let savingsTrendYDataCat10 = [<?php if(isset($savingsArrayC[9][0]) && !preg_match('/^(.*\s+.*)+$/', $savingsArrayC[9][1])) for($x=1; $x<sizeof($savingsArrayC[0]); $x++) echo "'{$savingsArrayC[9][$x]}', "; ?>];
        chartGenerator.generateLineChart('savingsTrendChartActualC', 'Cumulative trend of savings', backgroundColorTransp10, savingsGoalData, savingsTrendYLabels, savingsTrendYCategoryLabels, savingsTrendYDataCat1, savingsTrendYDataCat2, savingsTrendYDataCat3, savingsTrendYDataCat4, savingsTrendYDataCat5, savingsTrendYDataCat6, savingsTrendYDataCat7, savingsTrendYDataCat8, savingsTrendYDataCat9, savingsTrendYDataCat10);
    <?php endif; ?>
</script>