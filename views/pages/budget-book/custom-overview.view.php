<!-- ADDITIONAL-AREA -->
<section class="custom-additional-container">
    
<!-- #TODO: Comparison Values -->
<!-- comparisonTimeinterval (Alltime, All until now, All of year XXX, All from XXXX to XXXX) -->
<!-- timespan all time (days, months, years) -->
<!-- cashflow (also per day, per month, per year) -->
<!-- revenues (also per day, per month, per year) -->
<!-- fixed revenues (also per day, per month, per year) -->
<!-- expenditures (also per day, per month, per year) -->
<!-- fixed expenditures (also per day, per month, per year) -->

<!-- 1st row = days, weeks, months, years of Custom Interval -->
<!-- Then 5 columns -->
</section>

<!-- ENTRY-LIST -->
<section class="custom-list-container">
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
                <!-- #TODO: hidden until custom ist selected -->
                <span class="option-span">
                    <label for="cStartDate" class="dateLabel">from:</label>
                    <input type="date" name="cStartDate" id="cStartDate" max="<?php date('Y-m-d'); ?>" value="<?php echo $startDate; ?>">
                </span>
                <span class="option-span">
                    <label for="cStartDate" class="dateLabel">to:</label>
                    <input type="date" name="cEndDate" id="cEndDate" max="<?php date('Y-m-d'); ?>" value="<?php echo $endDate; ?>">
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
                <!-- #TODO: hidden until custom ist selected -->
                <span class="option-span">
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
                <!-- #TODO: hidden until custom ist selected -->
                <span class="option-span"> 
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
                <!-- #TODO: hidden until custom ist selected -->
                <!-- #TODO: nouislider -->
                <span class="option-span">
                    <input type="number" name="fromAmount" id="cFromAmount" min="0" max="<?php echo $maxAmount; ?>" step="1" value="<?php echo $fromAmount; ?>">
                    <label for="cFromAmount" class="dateLabel">from:</label>
                </span>
                <span class="option-span">
                    <input type="number" name="toAmount" id="cToAmount" min="0" max="<?php echo $maxAmount; ?>" step="1" value="<?php echo $toAmount; ?>">
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
                <!-- #TODO: hidden until custom ist selected -->
                <span class="option-span"> 
                    <input type="text" name="cCommentQuery" maxlength="1024" placeholder="comment" value="<?php echo e($cCommentQuery); ?>">
                </span> 
            </div>
            <div class="btn-row">
                <input type="submit" value="Search" class="btn">     
            </div>
        </div>
    </form>
    </article>
    <script defer>
        document.getElementById('cTitle').addEventListener("click", e => {
            document.getElementById('searchSettingsForm').classList.toggle("hide");
            document.getElementById('cTitle').classList.toggle("titleSelected");
         });
    </script>
    
    <table id="customOverviewTable">
        <caption>List of all entries</caption>
        <thead>
            <tr>
                <td>No.</td>
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
            <?php $x = 0; ?>
            <?php foreach($entries AS $entry): ?>
                <?php $x++; ?>
                <tr class="<?php if($entry->income === 1) echo 'revenue'; else echo 'expenditure'; ?>">
                    <td><?php echo e($x); ?></td>
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
</section>