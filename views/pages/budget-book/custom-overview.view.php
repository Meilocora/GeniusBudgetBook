<!-- #TODO: Scroll Element -->
<!-- ADDITIONAL-AREA -->
<section class="custom-additional-container">
    <div class="charts-container-row">
        <h2>Shown Timeinterval</h2>
    </div>
    <div class="charts-container-row">
        <div class="report-small neutral">
            <span class="report-label">
                Days
            </span>
            <span class="report-content">
                <?php echo $timespanArray[0]; ?>
            </span>
        </div>
        <div class="report-small neutral">
            <span class="report-label">
                Weeks
            </span>
            <span class="report-content">
                <?php echo $timespanArray[1]; ?>
            </span>
        </div>
        <div class="report-small neutral">
            <span class="report-label">
                Months
            </span>
            <span class="report-content">
                <?php echo $timespanArray[2]; ?>
            </span>
        </div>
        <div class="report-small neutral">
            <span class="report-label">
                Years
            </span>
            <span class="report-content">
                <?php echo $timespanArray[3]; ?>
            </span>
        </div>
    </div>
    <!-- ========== CASHFLOW DATA ========== -->
    <div class="charts-container-row">
        <h2>All / Fixed Cashflow Data</h2>
    </div>  
        <div class="charts-container-row">
            <div class="report-small <?php if($balances['totalCashflow'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Total
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['totalCashflow'], '0', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small <?php if($balances['totalCashflow']/$timespan > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Per Day
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['totalCashflow']/$timespan, '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small neutral">
                <span class="report-label">
                    Average
                </span>
                <span class="report-content">
                    <?php echo number_format(($alltimeBalances['totalCashflow']/$timespanAccount*$timespan), '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small <?php if($averagePercentages['cashflow'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    VS Average
                </span>
                <span class="report-content">
                    <?php if($balances['totalCashflow'] !== 0.001 | $alltimeBalances['totalCashflow'] !== 0.001): ?>
                        <?php if($averagePercentages['cashflow'] > 0) echo "+"; ?>
                        <?php echo number_format($averagePercentages['cashflow'], '2', ',', '.') . '% '; ?>
                    <?php else: ?>
                        <?php echo '0%'; ?>
                    <?php endif; ?>
                </span>
            </div>
        </div>
        <div class="charts-container-row">
            <div class="report-small <?php if($fixedBalances['totalCashflow'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Total
                </span>
                <span class="report-content">
                    <?php echo number_format($fixedBalances['totalCashflow'], '0', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small <?php if($fixedBalances['totalCashflow']/$timespan > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Per Day
                </span>
                <span class="report-content">
                    <?php echo number_format($fixedBalances['totalCashflow']/$timespan, '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small neutral">
                <span class="report-label">
                    Average
                </span>
                <span class="report-content">
                    <?php echo number_format(($fixedAlltimeBalances['totalCashflow']/$timespanAccount*$timespan), '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small <?php if($averagePercentages['fixedCashflow'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    VS Average
                </span>
                <span class="report-content">
                     <?php if($fixedBalances['totalCashflow'] !== 0.001 | $fixedAlltimeBalances['totalCashflow'] !== 0.001): ?>
                        <?php if($averagePercentages['fixedCashflow'] > 0) echo "+"; ?>
                        <?php echo number_format($averagePercentages['fixedCashflow'], '2', ',', '.') . '% '; ?>
                    <?php else: ?>
                        <?php echo '0%'; ?>
                    <?php endif; ?>
                </span>
            </div>
        </div>
    <!-- ========== REVENUE DATA ========== -->
    <div class="charts-container-row">
        <h2>All / Fixed Revenue Data</h2>
    </div>  
        <div class="charts-container-row">
            <div class="report-small <?php if($balances['revenues'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Total
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['revenues'], '0', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small <?php if($balances['revenues']/$timespan > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Per Day
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['revenues']/$timespan, '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small neutral">
                <span class="report-label">
                    Average
                </span>
                <span class="report-content">
                    <?php echo number_format(($alltimeBalances['revenues']/$timespanAccount*$timespan), '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small <?php if($averagePercentages['revenues'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    VS Average
                </span>
                <span class="report-content">
                    <?php if($balances['revenues'] !== 0.001 | $alltimeBalances['revenues'] !== 0.001): ?>
                        <?php if($averagePercentages['revenues'] > 0) echo "+"; ?>
                        <?php echo number_format($averagePercentages['revenues'], '2', ',', '.') . '% '; ?>
                    <?php else: ?>
                        <?php echo '0%'; ?>
                    <?php endif; ?>
                </span>
            </div>
        </div>
        <div class="charts-container-row">
            <div class="report-small <?php if($fixedBalances['revenues'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Total
                </span>
                <span class="report-content">
                    <?php echo number_format($fixedBalances['revenues'], '0', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small <?php if($fixedBalances['revenues']/$timespan > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Per Day
                </span>
                <span class="report-content">
                    <?php echo number_format($fixedBalances['revenues']/$timespan, '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small neutral">
                <span class="report-label">
                    Average
                </span>
                <span class="report-content">
                    <?php echo number_format(($fixedAlltimeBalances['revenues']/$timespanAccount*$timespan), '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small <?php if($averagePercentages['fixedRevenues'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    VS Average
                </span>
                <span class="report-content">
                    <?php if($fixedBalances['revenues'] !== 0.001 | $fixedAlltimeBalances['revenues'] !== 0.001): ?>
                        <?php if($averagePercentages['fixedRevenues'] > 0) echo "+"; ?>
                        <?php echo number_format($averagePercentages['fixedRevenues'], '2', ',', '.') . '% '; ?>
                    <?php else: ?>
                        <?php echo '0%'; ?>
                    <?php endif; ?>
                </span>
            </div>
        </div>
         <!-- ========== EXPENDITURE DATA ========== -->
    <div class="charts-container-row">
        <h2>All / Fixed Expenditure Data</h2>
    </div>  
        <div class="charts-container-row">
            <div class="report-small <?php if($balances['expenditures'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Total
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['expenditures'], '0', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small <?php if($balances['expenditures']/$timespan > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Per Day
                </span>
                <span class="report-content">
                    <?php echo number_format($balances['expenditures']/$timespan, '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small neutral">
                <span class="report-label">
                    Average
                </span>
                <span class="report-content">
                    <?php echo number_format(($alltimeBalances['expenditures']/$timespanAccount*$timespan), '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small <?php if($averagePercentages['expenditures'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    VS Average
                </span>
                <span class="report-content">
                    <?php if($balances['expenditures'] !== 0.001 | $alltimeBalances['expenditures'] !== 0.001): ?>
                        <?php if($averagePercentages['expenditures'] > 0) echo "+"; ?>
                        <?php echo number_format($averagePercentages['expenditures'], '2', ',', '.') . '% '; ?>
                    <?php else: ?>
                        <?php echo '0%'; ?>
                    <?php endif; ?>
                </span>
            </div>
        </div>
        <div class="charts-container-row">
            <div class="report-small <?php if($fixedBalances['expenditures'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Total
                </span>
                <span class="report-content">
                    <?php echo number_format($fixedBalances['expenditures'], '0', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small <?php if($fixedBalances['expenditures']/$timespan > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    Per Day
                </span>
                <span class="report-content">
                    <?php echo number_format($fixedBalances['expenditures']/$timespan, '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small neutral">
                <span class="report-label">
                    Average
                </span>
                <span class="report-content">
                    <?php echo number_format(($fixedAlltimeBalances['expenditures']/$timespanAccount*$timespan), '2', ',', '.') . '€ '; ?>
                </span>
            </div>
            <div class="report-small <?php if($averagePercentages['fixedExpenditures'] > 0) echo "positive"; else echo "negative" ?>">
                <span class="report-label">
                    VS Average
                </span>
                <span class="report-content">
                    <?php if($fixedBalances['expenditures'] !== 0.001 | $fixedAlltimeBalances['expenditures'] !== 0.001): ?>
                        <?php if($averagePercentages['fixedExpenditures'] > 0) echo "+"; ?>
                        <?php echo number_format($averagePercentages['fixedExpenditures'], '2', ',', '.') . '% '; ?>
                    <?php else: ?>
                        <?php echo '0%'; ?>
                    <?php endif; ?>
                </span>
            </div>
        </div>
</section>

<!-- ==================== ENTRY-LIST ==================== -->
<section class="custom-main-container">
    <div class="custom-list-container">
        <h2 id="cTitle">
            Search settings
            <input type='image' src='./img/arrow_right.png' alt='Arrow symbol' height='13px' width='13px'>
        </h2>
        <article id="searchSettingsForm" class="hide">
        <form action="./?route=custom-overview" method="POST">
            <div class="options-box">
                <div class="option">
                    <span class="option-span">
                        Timeinterval
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cTimeinterval" value="YTD" id="cYTD" <?php if($cTimeinterval === 'YTD') echo 'checked'; ?>>
                        <label for="cYTD">Year-to-Date</label>
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cTimeinterval" value="YoY" id="cYoY" <?php if($cTimeinterval === 'YoY') echo 'checked'; ?>>
                        <label for="cYoY">Year-on-Year</label>
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cTimeinterval" value="All" id="cAll" <?php if($cTimeinterval === 'All') echo 'checked'; ?>>
                        <label for="cAll">Since first entry</label>
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cTimeinterval" value="Custom" id="cCustom" <?php if($cTimeinterval === 'Custom') echo 'checked'; ?>>
                        <label for="cCustom">Custom interval</label>
                    </span> 
                    <span class="option-span hidden" id="fromDateSpan">
                        <label for="cStartDate" class="dateLabel">from:</label>
                        <input type="date" name="cStartDate" id="cStartDate" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $startDate; ?>">
                    </span>
                    <span class="option-span hidden" id="toDateSpan">
                        <label for="cEndDate" class="dateLabel">to:</label>
                        <input type="date" name="cEndDate" id="cEndDate" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $endDate; ?>">
                    </span>
                </div>
                <div class="option">
                    <span class="option-span">
                        Type
                    </span>
                    <span class="option-span">
                        <input type="radio" name="cEntryType" value="AllTypes" id="cAllTypes" <?php if($cEntryType === 'AllTypes') echo 'checked'; ?>>
                        <label for="cAllTypes">All</label>
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cEntryType" value="revenues" id="cRevenues" <?php if($cEntryType === 'revenues') echo 'checked'; ?>>
                        <label for="cYTD">Only revenues</label>
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cEntryType" value="expenditures" id="cExpenditures" <?php if($cEntryType === 'expenditures') echo 'checked'; ?>>
                        <label for="cYTD">Only expenditures</label>
                    </span> 
                </div>
                <div class="option">
                    <span class="option-span">
                        Fixation
                    </span>
                    <span class="option-span">
                        <input type="radio" name="cFixation" value="AllFixations" id="cAllFications" <?php if($cFixation === 'AllFixations') echo 'checked'; ?>>
                        <label for="cAllTypes">All</label>
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cFixation" value="fixed" id="cFixed" <?php if($cFixation === 'fixed') echo 'checked'; ?>>
                        <label for="cFixed">Only fixed</label>
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cFixation" value="unfixed" id="cUnfixed" <?php if($cFixation === 'unfixed') echo 'checked'; ?>>
                        <label for="cUnfixed">Only unfixed</label>
                    </span> 
                </div>
                <div class="option">
                    <span class="option-span">
                        Category
                    </span>
                    <span class="option-span">
                        <input type="radio" name="cCategories" value="allCategories" id="cAllCategories" <?php if($cCategories === 'allCategories') echo 'checked'; ?>>
                        <label for="cAllCategories">All categories</label>
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cCategories" value="certainCategory" id="cCertainCategory" <?php if($cCategories === 'certainCategory') echo 'checked'; ?>>
                        <label for="cCertainCategory">Certain category</label>
                    </span> 
                    <span class="option-span hidden" id="categorySpan">
                        <select name="cCategoryQuery">
                            <?php if($cCategoryQuery !== null): ?>
                                <option value="<?php echo e($cCategoryQuery); ?>"><?php echo e($cCategoryQuery); ?></option>
                            <?php endif; ?>
                            <?php foreach($categories AS $category): ?>
                                <?php if($category !== $cCategoryQuery): ?>
                                    <option value="<?php echo e($category); ?>"><?php echo e($category); ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>   
                    </span> 
                </div>
                <div class="option">
                    <span class="option-span">
                        Title
                    </span>
                    <span class="option-span">
                        <input type="radio" name="cTitles" value="allTitles" id="cAllTitles" <?php if($cTitles === 'allTitles') echo 'checked'; ?>>
                        <label for="cAllTitles">All titles</label>
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cTitles" value="certainTitle" id="cCertainTitle" <?php if($cTitles === 'certainTitle') echo 'checked'; ?>>
                        <label for="cCertainTitle">Certain title</label>
                    </span> 
                    <span class="option-span hidden" id="titleSpan"> 
                        <input type="text" name="cTitleQuery" maxlength="50" placeholder="title" value="<?php echo e($cTitleQuery); ?>">
                    </span> 
                </div>
                <div class="option">
                    <span class="option-span">
                        Amount
                    </span>
                    <span class="option-span">
                        <input type="radio" name="cAmounts" value="allAmounts" id="cAllAmounts" <?php if($cAmounts === 'allAmounts') echo 'checked'; ?>>
                        <label for="cAllAmounts">All amounts</label>
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cAmounts" value="Custom" id="cCustomAmount" <?php if($cAmounts === 'Custom') echo 'checked'; ?>>
                        <label for="cCustomAmount">Custom amount</label>
                    </span> 
                    <!-- #TODO: nouislider -->
                    <span class="option-span hidden" id="fromAmountSpan">
                        <input type="number" name="fromAmount" id="cFromAmount" min="0" max="<?php echo $maxAmount; ?>" step="1" value="<?php if($fromAmount !== null) echo $fromAmount; else echo 0; ?>">
                        <label for="cFromAmount" class="dateLabel">from:</label>
                    </span>
                    <span class="option-span hidden" id="toAmountSpan">
                        <input type="number" name="toAmount" id="cToAmount" min="0" max="<?php echo $maxAmount; ?>" step="1" value="<?php if($toAmount !== null) echo $toAmount; else echo 0; ?>">
                        <label for="cToAmount" class="dateLabel">to:</label>
                    </span>
                </div>
                <div class="option">
                    <span class="option-span">
                        Comment
                    </span>
                    <span class="option-span">
                        <input type="radio" name="cComments" value="allComments" id="cAllComments" <?php if($cComments === 'allComments') echo 'checked'; ?>>
                        <label for="cAllComments">All comments</label>
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cComments" value="noComments" id="cNoComments" <?php if($cComments === 'noComments') echo 'checked'; ?>>
                        <label for="cNoComments">No comments</label>
                    </span> 
                    <span class="option-span">
                        <input type="radio" name="cComments" value="certainComment" id="cCertainComment" <?php if($cComments === 'certainComment') echo 'checked'; ?>>
                        <label for="cCertainComment">Certain comment</label>
                    </span> 
                    <span class="option-span hidden" id="commentSpan"> 
                        <input type="text" name="cCommentQuery" maxlength="1024" placeholder="comment" value="<?php echo e($cCommentQuery); ?>">
                    </span> 
                </div>
                <div class="btn-row">
                    <input type="submit" value="Search" class="btn">     
                </div>
            </div>
        </form>
        </article>
        <!-- #TODO: Outsource JS -->
        <script defer>
            document.getElementById('cTitle').addEventListener("click", e => {
                document.getElementById('searchSettingsForm').classList.toggle("hide");
                document.getElementById('cTitle').classList.toggle("titleSelected");
            });

            // DEFAULT FOR WINDOW
            window.addEventListener("load", e => {
                if(document.getElementById('cCustom').checked & document.getElementById('fromDateSpan').classList.contains('hidden') & document.getElementById('toDateSpan').classList.contains('hidden')) {
                    document.getElementById('fromDateSpan').classList.toggle("hidden");
                    document.getElementById('toDateSpan').classList.toggle("hidden");
                }
                if(document.getElementById('cCertainCategory').checked & document.getElementById('categorySpan').classList.contains('hidden')) {
                    document.getElementById('categorySpan').classList.toggle("hidden");
                }
                if(document.getElementById('cCertainTitle').checked & document.getElementById('titleSpan').classList.contains('hidden')) {
                    document.getElementById('titleSpan').classList.toggle("hidden");
                }
                if(document.getElementById('cCustomAmount').checked & document.getElementById('fromAmountSpan').classList.contains('hidden') & document.getElementById('toAmountSpan').classList.contains('hidden')) {
                    document.getElementById('fromAmountSpan').classList.toggle("hidden");
                    document.getElementById('toAmountSpan').classList.toggle("hidden");
                }
                if(document.getElementById('cCertainComment').checked & document.getElementById('commentSpan').classList.contains('hidden')) {
                    document.getElementById('commentSpan').classList.toggle("hidden");
                }
            });

            // TIMEINTERVAL
            document.getElementById('cCustom').addEventListener("click", e => {
                if(document.getElementById('cCustom').checked & document.getElementById('fromDateSpan').classList.contains('hidden') & document.getElementById('toDateSpan').classList.contains('hidden')) {
                    document.getElementById('fromDateSpan').classList.toggle("hidden");
                    document.getElementById('toDateSpan').classList.toggle("hidden");
                }
            });

            document.getElementById('cAll').addEventListener("click", e => {
                if(!document.getElementById('fromDateSpan').classList.contains('hidden') & !document.getElementById('toDateSpan').classList.contains('hidden')) {
                    document.getElementById('fromDateSpan').classList.toggle("hidden");
                    document.getElementById('toDateSpan').classList.toggle("hidden");
                }
            });

            document.getElementById('cYoY').addEventListener("click", e => {
                if(!document.getElementById('fromDateSpan').classList.contains('hidden') & !document.getElementById('toDateSpan').classList.contains('hidden')) {
                    document.getElementById('fromDateSpan').classList.toggle("hidden");
                    document.getElementById('toDateSpan').classList.toggle("hidden");
                }
            });

            document.getElementById('cYTD').addEventListener("click", e => {
                if(!document.getElementById('fromDateSpan').classList.contains('hidden') & !document.getElementById('toDateSpan').classList.contains('hidden')) {
                    document.getElementById('fromDateSpan').classList.toggle("hidden");
                    document.getElementById('toDateSpan').classList.toggle("hidden");
                }
            });

            // CATEGORY
            document.getElementById('cCertainCategory').addEventListener("click", e => {
                if(document.getElementById('cCertainCategory').checked & document.getElementById('categorySpan').classList.contains('hidden')) {
                    document.getElementById('categorySpan').classList.toggle("hidden");
                }
            });

            document.getElementById('cAllCategories').addEventListener("click", e => {
                if(!document.getElementById('categorySpan').classList.contains('hidden')) {
                    document.getElementById('categorySpan').classList.toggle("hidden");
                }
            });

            // TITLE
            document.getElementById('cCertainTitle').addEventListener("click", e => {
                if(document.getElementById('cCertainTitle').checked & document.getElementById('titleSpan').classList.contains('hidden')) {
                    document.getElementById('titleSpan').classList.toggle("hidden");
                }
            });

            document.getElementById('cAllTitles').addEventListener("click", e => {
                if(!document.getElementById('titleSpan').classList.contains('hidden')) {
                    document.getElementById('titleSpan').classList.toggle("hidden");
                }
            });

            // AMOUNT
            document.getElementById('cCustomAmount').addEventListener("click", e => {
                if(document.getElementById('cCustomAmount').checked & document.getElementById('fromAmountSpan').classList.contains('hidden') & document.getElementById('toAmountSpan').classList.contains('hidden')) {
                    document.getElementById('fromAmountSpan').classList.toggle("hidden");
                    document.getElementById('toAmountSpan').classList.toggle("hidden");
                }
            });

            document.getElementById('cAllAmounts').addEventListener("click", e => {
                if(!document.getElementById('fromAmountSpan').classList.contains('hidden') & !document.getElementById('toAmountSpan').classList.contains('hidden')) {
                    document.getElementById('fromAmountSpan').classList.toggle("hidden");
                    document.getElementById('toAmountSpan').classList.toggle("hidden");
                }
            });

            // COMMENT
            document.getElementById('cCertainComment').addEventListener("click", e => {
                if(document.getElementById('cCertainComment').checked & document.getElementById('commentSpan').classList.contains('hidden')) {
                    document.getElementById('commentSpan').classList.toggle("hidden");
                }
            });

            document.getElementById('cNoComments').addEventListener("click", e => {
                if(!document.getElementById('commentSpan').classList.contains('hidden')) {
                    document.getElementById('commentSpan').classList.toggle("hidden");
                }
            });

            document.getElementById('cAllComments').addEventListener("click", e => {
                if(!document.getElementById('commentSpan').classList.contains('hidden')) {
                    document.getElementById('commentSpan').classList.toggle("hidden");
                }
            });
        </script>
        
        <table id="customOverviewTable">
            <caption>List of all <?php echo $countEntries; ?> matching entries</caption>
            <thead>
                <tr>
                    <td>
                        Category
                        <?php echo $sortButtons[0]; ?>
                    </td>
                    <td>
                        Title
                        <?php echo $sortButtons[1]; ?>
                    </td>
                    <td>
                        Amount
                        <?php echo $sortButtons[2]; ?>
                    </td>
                    <td>
                        Date
                        <?php echo $sortButtons[3]; ?>
                    </td>
                    <td>Comment</td>
                </tr>
            </thead>
            <tbody>
                <tr class="searchSettings">
                    <td>
                        <form action="./?route=custom-overview" method="POST">
                            <input type="hidden" name="cCategories" value="certainCategory">
                            <select name="cCategoryQuery" required>
                                <?php if($cCategoryQuery !== null): ?>
                                    <option value="<?php echo e($cCategoryQuery); ?>"><?php echo e($cCategoryQuery); ?></option>
                                <?php else: ?>
                                    <option disabled selected><?php echo 'Choose Category'; ?></option>
                                <?php endif; ?>
                                <?php foreach($categories AS $category): ?>
                                    <?php if($category !== $cCategoryQuery): ?>
                                        <option value="<?php echo e($category); ?>"><?php echo e($category); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select> 
                            <input type='image' src='./img/checkmark.png' alt='Checkmark to change the month and year' height='30px' width='30px'>
                        </form>  
                    </td>
                    <td>
                        <form action="./?route=custom-overview" method="POST">
                            <input type="hidden" name="cTitles" value="certainTitle">
                            <input type="text" name="cTitleQuery" maxlength="50" placeholder="search title" value="<?php echo e($cTitleQuery); ?>" required>
                            <input type='image' src='./img/checkmark.png' alt='Checkmark to change the month and year' height='30px' width='30px'>
                        </form>
                    </td>
                    <td>
                        <form action="./?route=custom-overview" method="POST">
                            <input type="hidden" name="cAmounts" value="Custom">
                            <input type="hidden" name="fromAmount" value="0">
                            <input type="text" placeholder="insert max Amount" name="toAmount" min ="1" max="<?php echo $maxAmount; ?>" onfocus="(this.type='number')" onblur="(this.type='text')" <?php if($toAmount !== 0) echo "value='{$toAmount}'"; ?> required>
                            <input type='image' src='./img/checkmark.png' alt='Checkmark to change the month and year' height='30px' width='30px'>
                        </form>
                    </td>
                    <td>
                        <form action="./?route=custom-overview" method="POST">
                            <select name="cTimeinterval" required> 
                                <?php $timeintervals = ['YTD', 'YoY', 'All']; ?>
                                <option value="<?php echo e($cTimeinterval); ?>"><?php echo e($cTimeinterval); ?></option>
                                    <?php foreach($timeintervals AS $interval): ?>
                                        <?php if($interval !== $cTimeinterval): ?>
                                            <option value="<?php echo e($interval); ?>"><?php echo e($interval); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                            </select> 
                            <input type='image' src='./img/checkmark.png' alt='Checkmark to change the month and year' height='30px' width='30px'>
                        </form>
                    </td>
                    <td>
                        <form action="./?route=custom-overview" method="POST">
                            <input type="hidden" name="cComments" value="certainComment">
                            <input type="text" name="cCommentQuery" maxlength="1024" placeholder="search comment" value="<?php echo e($cCommentQuery); ?>" required>
                            <input type='image' src='./img/checkmark.png' alt='Checkmark to change the month and year' height='30px' width='30px'>
                        </form>
                    </td>
                </tr>
                <?php foreach($paginationEntries AS $entry): ?>
                    <tr class="<?php if($entry->income === 1) echo 'revenue'; else echo 'expenditure'; ?>">
                        <td><?php echo e($entry->category); ?></td>
                        <td><?php echo e($entry->title); ?></td>
                        <td><?php echo number_format(e($entry->amount), '0', ',', '.') . ' €'; ?></td>
                        <td><?php echo e($entry->dateslug); ?></td>
                        <td><?php echo e($entry->comment); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td class="table-span" id="cPagination">
                    <?php if($numPages > 1): ?>
                        <a href="./?route=custom-overview&<?php echo http_build_query(['page' => 1]); ?>#customOverviewTable">
                            <img src="./img/arrow_end_left.png" alt="Arrow symbol that points to the left" id="pagination_arrow_left">
                        </a>
                        <a href="./?route=custom-overview&<?php echo http_build_query(['page' => max([1, $currentPage-1])]); ?>#customOverviewTable">
                            <img src="./img/arrow_one_left.png" alt="Arrow symbol that points to the left" id="pagination_arrow_left">
                        </a>
                    <?php endif; ?>
                    <?php if($numPages > 1): ?>
                            <?php for($x=1; $x<= $numPages; $x++): ?>
                                <a  href="./?route=custom-overview&<?php echo http_build_query(['page' => $x]); ?>#customOverviewTable"
                                    class="hidden pagination-a <?php if($currentPage === $x) echo 'pagination-active'; ?>"><?php echo e($x); ?></a>
                            <?php endfor; ?>
                    <?php endif; ?>
                    <?php if($numPages > 1): ?>
                        <a href="./?route=custom-overview&<?php echo http_build_query(['page' => min([$numPages, $currentPage+1])]); ?>#customOverviewTable">
                            <img src="./img/arrow_one_right.png" alt="Arrow symbol that points to the left" id="pagination_arrow_left">
                        </a>
                        <a href="./?route=custom-overview&<?php echo http_build_query(['page' => $numPages]); ?>#customOverviewTable">
                            <img src="./img/arrow_end_right.png" alt="Arrow symbol that points to the right" id="pagination_arrow_right">
                        </a>
                    <?php endif; ?>
                    </span>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td id="cPagination-setting">
                    <form action="./?route=custom-overview" method="POST">
                            <label for="perPage">Max entries per page:</label>
                            <input type="number" id="cPerPage" name="perPage" min="5" max="150" step="5" value="<?php echo e($perPage); ?>">
                            <input type='image' src='./img/checkmark.png' alt='Checkmark to change the month and year' height='20px' width='20px'>
                        </form>
                    </td>
                </tr>
            </tfoot>
        </table>
        <script defer>
            let pages = document.querySelectorAll('.pagination-a');
            let activePage = document.querySelector('.pagination-active');
            let pageBefore = activePage.previousElementSibling;
            let twoBefore = pageBefore.previousElementSibling;
            let pageAfter = activePage.nextElementSibling;
            let twoAfter = pageAfter.nextElementSibling;
            
            activePage.classList.toggle("hidden");
            if(pageBefore.classList.contains('pagination-a')) pageBefore.classList.toggle("hidden");
            if(pageAfter.classList.contains('pagination-a')) pageAfter.classList.toggle("hidden");
            if(!pageBefore.classList.contains('pagination-a') & twoAfter.classList.contains('pagination-a')) twoAfter.classList.toggle("hidden");
            if(!pageAfter.classList.contains('pagination-a') & twoBefore.classList.contains('pagination-a')) twoBefore.classList.toggle("hidden");
        </script>
    </div>
    <!-- CHARTS-AREA -->    
    <div class="charts-container">
        <div class="charts-container-row">
            <h1>Custom Chart</h1>
        </div>
        <form action="./?route=custom-overview" method="POST" id="cChartForm">
            <div class="charts-container-row">
                <span>Choose search settings:</span>
            </div>
            <div class="charts-container-row">
            <input type="radio" id="cChartCategory" name="cChartSearch" value="category" <?php if($cChartSearch === 'category') echo 'checked'; ?>>
                <label for="cChartCategory">Search by Category</label>
                    <select name="cChartSearchCategory">
                        <?php if($cChartSearchCategory !== null): ?>
                            <option value="<?php echo e($cChartSearchCategory); ?>"><?php echo e($cChartSearchCategory); ?></option>
                        <?php else: ?>
                            <option disabled selected><?php echo 'Choose Category'; ?></option>
                        <?php endif; ?>
                        <?php foreach($categories AS $category): ?>
                            <?php if($category !== $cChartSearchCategory): ?>
                                <option value="<?php echo e($category); ?>"><?php echo e($category); ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>  
            </div>
            <div class="charts-container-row">
            <input type="radio" id="cChartTitle" name="cChartSearch" value="title" <?php if($cChartSearch === 'title') echo 'checked'; ?>>
                <label for="cChartTitle">Search by Title</label>
                <input type="text" name="cChartSearchRegex" maxlength="50" placeholder="search title" value="<?php if($cChartSearch === 'title') echo e($cChartSearchRegex);?>">
            </div>
            <div class="charts-container-row">
                <label for="cChartStartDate" class="dateLabel">From Month:</label>
                <input type="month" name="cChartStartDate" id="cChartStartDate" max="<?php echo date('Y-m'); ?>" value="<?php echo $cChartStartDate; ?>" required>
            </div>
            <div class="charts-container-row">
                <label for="cChartEndDate" class="dateLabel">To Month:</label>
                <input type="month" name="cChartEndDate" id="cChartEndDate" max="<?php echo date('Y-m'); ?>" value="<?php echo $cChartEndDate; ?>" required>
            </div>
            <div class="charts-container-row">
                <input type="submit" value="Search" class="btn"> 
            </div>
        </form>
        <div class="charts-container-row">
            <canvas id="customTrend" width="950px" height="400px"></canvas>
        </div>
    </div>
</section>
<!-- #TODO: Chart Area to show the trend of 1 category or title within a month timeinterval in a line chart -->
<!-- Also area, that shows the most important values (max value, final value, count entries, share of all revenues/ axpenditures, increase from first to last) -->