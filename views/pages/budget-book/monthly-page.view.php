<section class="form-container" id="entry-form">
    <form action="./?route=monthly-page/create" method="POST" class="form">
        <h1>Add Entry for <?php echo $datePretty; ?></h1>
        <div class="form-row">
            <label for="category" class="label">Category</label>
            <select name="category" id="category" class="input">
                <?php foreach($categories AS $category): ?>
                    <option value="<?php echo e($category); ?>"><?php echo e($category); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <label for="title" class="label">Title</label>
            <input type="text" name="title" id="title" class="input" required>
        </div>
        <div class="form-row">
            <label for="amount" class="label">Amount</label>
            <input type="number" name="amount" id="amount" class="input" min="0.01" step="0.01" required>
        </div>
        <div class="form-row">
            <label for="date" class="label">Date</label>
            <input type="date" name="date" id="date" class="input" value="<?php echo $date . '-' . date('d'); ?>" required>
        </div>
        <div class="form-row">
            <label for="comment" class="label">Comment</label>
            <textarea name="comment" id="comment" cols="30" rows="2" class="input" style="resize: none;"></textarea>
        </div>
        <div class="form-row fixedentryform">
            <label for="fixedentry">Fixed Entry?</label>
            <input type="checkbox" name="fixedentry" id="fixedentry" value="1"></input>
            <span class="infofixedentrycheckbox">Check this box to create a 'fixed entry', that can easily be transfered to the entry list of the next month.</span>
        </div>
        <div class="form-row">
            <input type="submit" value="Submit" class="btn">
        </div>
    </form>
</section>
<!-- ADDITIONAL AREA -->
<section class="additional-container">
    <div class="additional-row">
        <h2>Balance sheet 
            <form action="./?route=monthly-page" method="post" id="dateChange">
                <input type="month" name="month_year" max="<?php echo date('Y-m'); ?>" value="<?php echo (new DateTime($datePretty))->format('Y-m'); ?>">
                <input type='image' src='./img/checkmark.png' alt='Checkmark to change the month and year' height='20px' width='20px'>
            </form>
        </h2>
    </div>
    <div class="additional-row">
        <h3>Balances of wealth distribution categories</h3>
    </div>
    <div class="additional-row">
        <span class="list-caption">target value &emsp; actual value</span>
    </div>
    <div class="additional-row">
        <form action="./?route=monthly-page/adjustwd" method="POST">
            <?php for($x = 0; $x < sizeof($wdcategories); $x = $x+3): ?>
            <span>
                    <label class="additional-label" for="<?php echo $wdcategories[$x]; ?>"><?php echo $wdcategories[$x]; ?></label>
                    <input class="additional-input" type="number" max="999999" step="1" id ="<?php echo $wdcategories[$x] . '-target'; ?>" name="<?php echo $wdcategories[$x] . '-target'; ?>" value="<?php echo $wdcategories[$x+1]; ?>">
                    <input class="additional-input" type="number" max="999999" step="1" id ="<?php echo $wdcategories[$x] . '-actual'; ?>" name="<?php echo $wdcategories[$x] . '-actual'; ?>" value="<?php echo $wdcategories[$x+2]; ?>">
            </span>
            <?php endfor; ?>
            <input type="hidden" name="date" value="<?php echo $datePretty; ?>">
            <input type="submit" value="Adjust" class="btn">
        </form>
    </div>
    <hr>
    <div class="additional-row">
        <h3>Balance of budget book</h3>     
    </div>
    <div class="additional-row">
        <span class="circle">Fixed income<br><?php echo e(number_format($balance['fixedIncome'], '0', ',', '.')); ?> €</span>
        <span class="circle">Total income<br><?php echo e(number_format($balance['income'], '0', ',', '.')); ?> €</span>  
    </div>
    <div class="additional-row">
        <span class="circle">Fixed expenses<br><?php echo e(number_format($balance['fixedExpenses'], '0', ',', '.')); ?> €</span>
        <span class="circle">Total expenses<br><?php echo e(number_format($balance['expenses'], '0', ',', '.')); ?> €</span>  
    </div>
    <div class="additional-row">
    <span class="<?php if($balance['fixedBalance'] >= 0) {echo 'pos';} else {echo 'neg';}?> circle">Fixed balance<br><?php echo e(number_format($balance['fixedBalance'], '0', ',', '.')); ?> €</span>
        <span class="<?php if($balance['balance'] >= 0) {echo 'pos';} else {echo 'neg';}?> circle">Total balance<br><?php echo e(number_format($balance['balance'], '0', ',', '.')); ?> €</span>
    </div>
</section>
<!-- ENTRY-LIST -->
<section class="list-container" id="monthly-table">
    <form action="./?route=monthly-page/fixedentries" method="POST">
        <input type="hidden" name="date" value="<?php echo (new DateTime($datePretty))->format('Y-m'); ?>">
        <input type="submit" value="Transfer fixed entries" <?php if(!$transferPossible) echo 'disabled'; ?>
               class="<?php if($transferPossible) echo 'transferActive'; else echo 'transferInactive'; ?> btn-transfer">
    </form>
    <div class="table-row">
        <h1>List of all entries for <?php echo $datePretty; ?></h1>
    </div>
    <div class="table-row">
        <span class="table-span">
            Category
            <?php echo $sortButtons[0]; ?>
        </span>
        <span class="table-span">
            Title
            <?php echo $sortButtons[1]; ?>
        </span>
        <span class="table-span">
            Amount
            <?php echo $sortButtons[2]; ?>
        </span>
        <span class="table-span">
            Date
            <?php echo $sortButtons[3]; ?>
        </span>
        <span class="table-span">
            Comment
        </span>
        <span class="table-span">
            Action
        </span>
    </div>

    <!-- List of Entries for this month -->
    <?php if(!empty($entries)): ?>
        <?php foreach($entries AS $entry): ?>
            <div class="table-row <?php if($entry->fixedentry === 1) echo 'fixedentry'; ?>">
                <?php if(!isset($_GET['edit'])) $_GET['edit'] = '0'; ?>
                    <span class="table-span">
                        <select name="category"
                        <?php if($_GET['edit'] != $entry->id) echo 'disabled'; ?>
                        <?php if($_GET['edit'] == $entry->id) echo 'class="edit"'; ?>
                        id="<?php echo e($entry->id); ?>-category">
                        <option value="<?php echo e($entry->category); ?>"><?php echo e($entry->category); ?></option>
                        <?php foreach($categories AS $category): ?>
                            <option value="<?php echo e($category); ?>"><?php echo e($category); ?></option>
                        <?php endforeach; ?>
                        </select>    
                    </span>
                <span class="table-span">
                    <input  type="text" 
                            name="title" 
                            value="<?php echo e($entry->title); ?>" 
                            <?php if($_GET['edit'] != $entry->id) echo 'readonly'; ?>
                            <?php if($_GET['edit'] == $entry->id) echo 'class="edit"'; ?>
                            id="<?php echo e($entry->id); ?>-title">
                </span>
                <span class="table-span">
                    <input  type="text" 
                            name="amount" 
                            value="<?php echo e($entry->amount); ?>" 
                            <?php if($_GET['edit'] != $entry->id) echo 'readonly'; ?>
                            <?php if($_GET['edit'] == $entry->id) echo 'class="edit"'; ?>
                            id="<?php echo e($entry->id); ?>-amount">
                </span>
                <span class="table-span">
                    <input  type="text" 
                            name="dateslug" 
                            value="<?php echo e($entry->dateslug); ?>" 
                            <?php if($_GET['edit'] != $entry->id) echo 'class="" readonly'; ?>
                            <?php if($_GET['edit'] == $entry->id) echo 'class="edit"'; ?>
                            id="<?php echo e($entry->id); ?>-dateslug">
                </span>
                <span class="table-span">
                    <input  type="text" 
                            name="comment" 
                            value="<?php echo e($entry->comment); ?>" 
                            <?php if($_GET['edit'] != $entry->id) echo 'readonly'; ?>
                            <?php if($_GET['edit'] == $entry->id) echo 'class="edit"'; ?>
                            id="<?php echo e($entry->id); ?>-comment">
                </span>
                <input  type="checkbox" 
                        name="fixedentry" 
                        value="1" 
                        <?php if($entry->fixedentry === 1) echo 'checked' ?>
                        class="boxfixedentry 
                        <?php if($_GET['edit'] != $entry->id) echo 'hidden'; ?>
                        <?php if($_GET['edit'] == $entry->id) echo 'edit'; ?>"
                        id="<?php echo e($entry->id); ?>-fixedentry">
                <span class="table-span">
                    <?php if($_GET['edit'] != $entry->id): ?>
                        <form action="./?route=monthly-page/delete" method="POST" class="entry-list-form">
                            <input type="hidden" name="id" value="<?php echo e($entry->id); ?>">
                            <input type="submit" value="X" class="btn-small btn-delete">
                        </form>
                        <a href="./?route=monthly-page&edit=<?php echo e($entry->id); ?>#monthly-table" class="like-btn-small btn-small-hover">Edit</a>
                    <?php else: ?>
                        <form action="./?route=monthly-page/update" method="POST" class="entry-list-form">
                            <input type="hidden" name="id" value="<?php echo e($entry->id); ?>">
                            <input type="hidden" name="category" id="<?php echo e($entry->id); ?>-newcategory">
                            <input type="hidden" name="title" id="<?php echo e($entry->id); ?>-newtitle">
                            <input type="hidden" name="amount" id="<?php echo e($entry->id); ?>-newamount">
                            <input type="hidden" name="dateslug" id="<?php echo e($entry->id); ?>-newdateslug">
                            <input type="hidden" name="comment" id="<?php echo e($entry->id); ?>-newcomment">
                            <input type="hidden" name="fixedentry" id="<?php echo e($entry->id); ?>-newfixedentry">
                            <input type="submit" value="Update" class="updateButton btn-small btn-small-hover">
                            <script type="module" src="./src/JS/monthlypagemain.js"></script>
                        </form>
                    <?php endif; ?>
                </span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="table-row">
        <span class="table-span" id="pagination">
        <a href="./?route=monthly-page&<?php echo http_build_query(['page' => 1]); ?>#monthly-table">
            <img src="./img/arrow_left.png" alt="Arrow symbol that points to the left" id="pagination_arrow_left" class="hidden">
        </a>
            <?php if($numPages > 1): ?>
                    <?php for($x=1; $x<= $numPages; $x++): ?>
                        <a  href="./?route=monthly-page&<?php echo http_build_query(['page' => $x]); ?>#monthly-table"
                            class="hidden pagination-a <?php if($currentPage === $x) echo 'pagination-active'; ?>"><?php echo e($x); ?></a>
                    <?php endfor; ?>
            <?php endif; ?>
        <a href="./?route=monthly-page&<?php echo http_build_query(['page' => $numPages]); ?>#monthly-table">
            <img src="./img/arrow_right.png" alt="Arrow symbol that points to the right" id="pagination_arrow_right" class="hidden">
        </a>
        </span>
    </div>
    <script>
        let pages = document.querySelectorAll('.pagination-a');
        let activePage = document.querySelector('.pagination-active');
        let pageBefore = activePage.previousElementSibling;
        let pageAfter = activePage.nextElementSibling;
        
        activePage.classList.toggle("hidden");
        if(pageBefore !== null) pageBefore.classList.toggle("hidden");
        if(pageAfter !== null) pageAfter.classList.toggle("hidden");
        if(activePage.innerHTML >= 3) document.getElementById('pagination_arrow_left').classList.toggle("hidden");
        if(pages.length - activePage.innerHTML >=2) document.getElementById('pagination_arrow_right').classList.toggle("hidden");
    </script>
    <span id="pagination-setting">
        <form action="./?route=monthly-page" method="POST">
            <label for="perPage">Max entries per page:</label>
            <input type="number" id="perPage" name="perPage" min="1" max="30" step="1" value="<?php echo e($perPage); ?>">
            <input type='image' src='./img/checkmark.png' alt='Checkmark to change the month and year' height='20px' width='20px'>
        </form>
    </span>
</section>