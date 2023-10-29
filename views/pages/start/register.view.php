<script type="module" src="./src/JS/registermain.php"></script>
<section class="container-register">
    <form action="./?route=register/newUser" class="form-register" method="POST">
        <h1 class="row-register" id="mainTitle">User Registration</h1>
        <?php if(isset($_POST['errorMessage'])): ?>
            <div class="error-box registry-error">
                <img src="./img/error.png" alt="Error Symbol" height="70px" width="70px">
                <h2>Oops, something went wrong!</h2>
                <span><?php echo $_POST['errorMessage']; ?></span>
            </div>
        <?php endif; ?>
        <div id="newUserdataContainer">
            <div class="row-register">
                <label for="newUsername" id="labelNewUsername">Choose a username:</label>
                <input type="text" name="newUsername" id="newUsername" maxlength="30" required>
            </div>
            <div class="row-register">
                <label for="newUserPw" id="labelNewUserPw">Choose a password:</label>
                <input type="password" name="newUserPw" id="newUserPw" maxlength="60" required>
            </div>
            <div class="btn-row">
                <input type="submit" value="Next" id="hideUserdata" class="btn">
                <a href="./" class="cancel-btn">Cancel</a>
            </div>
        </div>
        <!-- +++++++++++++ WEALTH DISTRIBUTION CATEGORIES +++++++++++++ -->
        <div id="wealthDistribution" class="hidden">
            <h3 class="row-register">Please set the main categories of you wealth distribution</h3>
            <div class="row-register">
                <label for="wealthdist1">Category 1:</label>
                <input type="text" name="wealthdist1" id="wealthdist1" placeholder="e.g. Bank account" maxlength="20">
                <input type="checkbox" name="wd1liquid" value="1" id="wd1liquid">
                <img src="./img/questionmark.png" height="12px" width="12px" alt="Questionmark symbol.">
                <span class="hidden">
                    Check to add this category to liquid assets. Liquid assets will be added to your savings balance.
                </span>
            </div>
            <div class="btn-row">
                <input type="submit" value="Add category" id="anotherWealthDistCat" class="btn">
            </div> 
            <div class="btn-row">
                <input type="submit" value="Next" id="hideWealthDist" class="btn">
                <input type="submit" value="Get Back" id="showUserdata" class="cancel-btn">
            </div>
        </div>
        <!-- +++++++++++++ REVENUE CATEGORIES +++++++++++++ -->
        <div id="revenueCategories" class="hidden">
            <h3 class="row-register">Please set your personal revenue categories</h3>
            <div class="row-register">
                <label for="revcat1">Category 1:</label>
                <input type="text" name="revcat1" id="revcat1" placeholder="e.g. Salary">
            </div>
            <div class="btn-row">
                <input type="submit" value="Add category" id="anotherRevCat" class="btn">
            </div>
            <div class="btn-row">
                <input type="submit" value="Next" id="hideRevenueCategories" class="btn">
                <input type="submit" value="Get Back" id="showWealthDist" class="cancel-btn">
            </div>
        </div>
        <!-- +++++++++++++ EXPENDITURE CATEGORIES +++++++++++++ -->
        <div id="expenditureCategories" class="hidden">
            <h3 class="row-register">Please set your personal expenditure categories</h3>
            <div class="row-register">
                <label for="expcat1">Category 1:</label>
                <input type="text" name="expcat1" id="expcat1" placeholder="e.g. Rent">
            </div>
            <div class="btn-row">
                <input type="submit" value="Add category" id="anotherExpCat" class="btn">
            </div>
            <div class="btn-row">
                <input type="submit" value="Next" id="hideExpenditureCategories" class="btn">
                <input type="submit" value="Get Back" id="showRevenueCategories" class="cancel-btn">
            </div>
        </div>
        <!-- +++++++++++++ GOALS +++++++++++++ -->
        <div id="goals" class="hidden">
            <h3 class="row-register">Please set your personal yearly donation goal</h3>
            <div class="row-register">
                <label for="donationgoal">Donation goal for <?php echo ((new DateTime())->format('Y')); ?>:</label>
                <input type="number" name="donationgoal" id="donationgoal" min="0" step="10" required>
            </div>
            <h3 class="row-register">Please set your personal yearly saving goal (only tracks liquid assets)</h3>
            <div class="row-register">
                <label for="savinggoal">Saving goal for <?php echo ((new DateTime())->format('Y')); ?>:</label>
                <input type="number" name="savinggoal" id="savinggoal" min="0" step="10" required>
            </div>
            <h3 class="row-register">Please set your personal yearly total wealth goal</h3>
            <div class="row-register">
                <label for="totalwealthgoal">Wealth goal for <?php echo ((new DateTime())->format('Y')); ?>:</label>
                <input type="number" name="totalwealthgoal" id="totalwealthgoal" min="0" step="100" required>
            </div>
            <div class="btn-row">
                <input type="submit" value="Complete registration" class="btn">
                <input type="submit" value="Get Back" id="showExpenditureCategories" class="cancel-btn">
            </div>
        </div>
    </form>
</section> 