<div class="scroll-container">
    <div class="scroll-element">
        <a href="#header-banner-name"><img src="./img/arrow_up.png" alt="Arrow symbol that points upwards" height='20px' width='20px'></a>    
        <a href="#cashflowContainer" class="scroll1">Total Cashflow</a>
        <a href="#donationsContainer" class="scroll2">Revenues</a>
        <a href="#savingsContainer" class="scroll3">Expenditures</a>
        <a href="#footer"><img src="./img/arrow_down.png" alt="Arrow symbol that points downwards" height='20px' width='20px'></a>
    </div>
</div>
<div class="adjustments-container">
    <img src="./img/gear.png" alt="Symbol of a gear" height='40px' width='40px' class="adjustmentsSymbol">
    <div class="adjustments-box">
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
            <span>Change charts colors:</span>
        </div>
        <div class="settings-row">
            <form action="./?route=overview" method="post" class="chartScopeForm">
                <input type="hidden" name="chartColorSet" value="default">
                <input type="submit" value="default" 
                class="<?php if($chartColorSet === 'default') echo 'chosen'; ?>">
            </form>
            <form action="./?route=overview" method="post" class="chartScopeForm">
                <input type="hidden" name="chartColorSet" value="colorful">
                <input type="submit" value="colorful" 
                class="<?php if($chartColorSet === 'colorful') echo 'chosen'; ?>">
            </form>
            <?php if(isset($_COOKIE['customChartColorTheme'])): ?>
            <form action="./?route=overview" method="post" class="chartScopeForm">
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
    <div class="charts-container" id="cashflowContainer">
        <div class="charts-container-row">
            <h1>Total Cashflow</h1>
        </div>
        <div class="charts-container-row">
            <span>For <?php echo $year; ?> your your total cashflow is <b><?php echo number_format($balances['totalCashflow'], '0', ',', '.') . '€ '; ?></b>.</span>
        </div>
        
        
        <!-- #TODO: Doughnut chart with revenues (consisting of fixed revenues and other revenues) + same of expenditures + total balance + saved money -->
        <!-- #TODO: Linechart with rev, exp and bilance over months compared to median rev, exp and bilance of the year so far -->
        <!-- #TODO: List of all Entries of given timeinterval with several search settings (only 1 category, only 1 title, only revenues, only expenditures, only fixed) -->
    </div>

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
                    <?php echo number_format(($fixedBalances['revenues']/$balances['revenues'])*100, '2', ',', '.') . '% '; ?>
                </span>
            </div>
            <div class="report positive">
                <span class="report-label">
                    Revenues p.d.
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['revenues']/(365- $daysleft), '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report neutral">
                <span class="report-label">
                    Compared to avg.
                </span>
                <span class="report-content">
                    <?php echo number_format(($balances['revenues']/($alltimeBalances['revenues']/$timespanAccount*(365-$daysleft))-1)*100, '2', ',', '.') . '% '; ?>
                </span>
            </div>
        </div>
        <!-- #TODO: Doughnut chart with revenue categories (€ & %) -->
        <!-- #TODO: Barchart with categories as bars -->
        <!-- #TODO: Line chart with revenue categories -->
    </div>

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
                    <?php echo number_format(($fixedBalances['expenditures']/$balances['expenditures'])*100, '2', ',', '.') . '% '; ?>
                </span>
            </div>
            <div class="report negative">
                <span class="report-label">
                    Expenditures p.d.
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['expenditures']/(365- $daysleft), '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report neutral">
                <span class="report-label">
                    Compared to avg.
                </span>
                <span class="report-content">
                    <?php echo number_format(($balances['expenditures']/($alltimeBalances['expenditures']/$timespanAccount*(365-$daysleft))-1)*100, '2', ',', '.') . '% '; ?>
                </span>
            </div>
        </div>
        <!-- #TODO: Doughnut chart with expenditure categories (€ & %) -->
        <!-- #TODO: Barchart with categories as bars -->
        <!-- #TODO: Line chart with expenditure categories -->
    </div>
</section>