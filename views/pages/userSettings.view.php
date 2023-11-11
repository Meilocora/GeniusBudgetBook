<script type="module" src="./src/JS/usersettingsmain.js"></script>
<section class="userSettings-container">
    <?php if (!empty($errorArray)): ?>
        <div class="error-box" id="userSettings_errorBox">
            <img src="./img/error.png" alt="Error Symbol" height="70px" width="70px">
            <h1>Oops something went wrong...</h1>
                <?php foreach ($errorArray as $error): ?>
                    <div class="userSettings-row">
                        <span><?php echo $error; ?></span>
                    </div>
                <?php endforeach; ?>
        </div>
        <script>
            let errorBox = document.querySelector("#userSettings_errorBox");
            errorBox.addEventListener("click", function() {
                errorBox.style.display = "none";
            });
        </script>
    <?php endif; ?>
    <div class="userSettings-row">
        <h1> Welcome <?php echo e($_SESSION['username']); ?> !</h1>
    </div>
    <div class="userSettings-row">  
        <span style="font-style: italic;">You can adjust general settings over here.</span>
    </div>
    <!-- ++++++++++ CHANGE USERNAME ++++++++++ -->
    <div class="userSettings-row">
        <h3 id="title_changeUsername">
            Change username
            <input type='image' src='./img/arrow_right.png' alt='Arrow symbol' height='13px' width='13px'>
        </h3>
    </div>
    <article id="usernameForm" class="hide">
        <form action="./?route=userSettings/changeUserData" method="POST" >
            <div class="userSettings-row">
                <input type="text" name="changedUsername" id="changedUsername" value="<?php echo e($_SESSION['username']); ?>" minlength="4" maxlength="30" required>
            </div>
            <div class="userSettings-row">
                <input type="submit" value="Send" class="btn">
            </div>
        </form>
    </article>
    <!-- ++++++++++ CHANGE PASSWORD ++++++++++ -->
    <div class="userSettings-row">
        <h3 id="title_changePassword">
            Change password
            <input type='image' src='./img/arrow_right.png' alt='Arrow symbol' height='13px' width='13px'>
        </h3>
    </div>
    <article id="passwordForm" class="hide">
        <hr class="thin-line">
        <form action="./?route=userSettings/changeUserData" method="POST" >
            <div class="userSettings-row">
                <label style="width: 200px;" for="currentPassword">Insert current password:</label>
                <input style="width: 200px;" type="password" name="currentPassword" id="currentPassword" minlength="8" maxlength="60" required>
            </div>
            <div class="userSettings-row">
                <label style="width: 200px;" for="changedPassword">Insert new password:</label>
                <input style="width: 200px;" type="password" name="changedPassword" id="changedPassword" minlength="8" maxlength="60" required>
                    
            </div>
            <div class="userSettings-row">
                <label style="width: 200px;" for="changedPassword2">Repeat new password:</label>
                <input style="width: 200px;" type="password" name="changedPassword2" id="changedPassword2"minlength="8" maxlength="60" required>
            </div>
            <hr class="thin-line">
            <div class="userSettings-row">
                <input type="submit" value="Send" class="btn">
            </div>
        </form>
    </article>
    <!-- ++++++++++ CHANGE COLOR-THEME ++++++++++ -->
    <?php $colorThemes = ['greenTheme', 'redTheme', 'blueTheme', 'customTheme']; ?>
    <div class="userSettings-row">
        <h3 id="title_changeColorTheme">
            Change color theme
            <input type='image' src='./img/arrow_right.png' alt='Arrow symbol' height='13px' width='13px'>
        </h3>
    </div>
    <article id="standardColorForm" class="hide">
        <div class="userSettings-row">
            <form action="./?route=userSettings/adjustColorTheme" method="POST" >
                <select name="colorTheme" id="colorTheme" class="select">
                    <option value="<?php echo $_COOKIE['colorTheme']; ?>">
                        <?php foreach($colorThemes AS $theme) if($theme === $_COOKIE['colorTheme']) echo ucfirst(preg_replace('/^(.*)Theme$/', '$1', $theme)); ?>
                    </option>
                    <?php foreach($colorThemes AS $theme): ?>
                        <?php if($theme !== $_COOKIE['colorTheme'] && $theme !== 'customTheme'): ?>
                            <option value="<?php echo $theme; ?>">
                                <?php echo ucfirst(preg_replace('/^(.*)Theme$/', '$1', $theme)); ?>
                            </option>
                        <?php elseif($theme !== $_COOKIE['colorTheme'] && $theme === 'customTheme' && isset($_COOKIE['customColorTheme'])):?>
                            <option value="customTheme">Custom</option>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </select> 
        </div>
        <div class="userSettings-row">
                <input type='image' src='./img/checkmark.png' alt='Checkmark symbol' height='30px' width='30px'>
            </form>
        </div>
    </article>
    <!-- ++++++++++ COLOR-THEME ++++++++++ -->
    <!-- #TODO: Randomize Button -->
    <div class="userSettings-row">
        <h3 id="title_colorTheme">
            Create a custom color theme
            <input type='image' src='./img/arrow_right.png' alt='Arrow symbol' height='13px' width='13px'>
        </h3>
    </div>
    <article id="customColorForm" class="hide">
        <hr class="thin-line">
        <form action="./?route=userSettings/adjustColorTheme" method="POST">
        <input type="hidden" name="customColorTheme" value="1">
        
        <div class="userSettings-row">
            <div class="userSettings-col">
                <label for="theme-color-light">Light color </label>
                <input type="color" name="theme-color-light" id="theme-color-light" value="<?php if(isset($_COOKIE['customColorTheme'])) echo e($customColorTheme = explode(',', $_COOKIE['customColorTheme'])[0]); ?>">
            </div>
            <div class="userSettings-col">
                <label for="theme-color-medium">Medium color </label>
                <input type="color" name="theme-color-medium" id="theme-color-medium" value="<?php if(isset($_COOKIE['customColorTheme'])) echo e($customColorTheme = explode(',', $_COOKIE['customColorTheme'])[1]); ?>">
            </div>
            <div class="userSettings-col">
                <label for="theme-color-heavy">Dark color </label>
                <input type="color" name="theme-color-heavy" id="theme-color-heavy" value="<?php if(isset($_COOKIE['customColorTheme'])) echo e($customColorTheme = explode(',', $_COOKIE['customColorTheme'])[2]); ?>">
            </div>
        </div>
        <div class="userSettings-row">
            <div class="userSettings-col">
                <label for="theme-color-neon">Neon color </label>
                <input type="color" name="theme-color-neon" id="theme-color-neon" value="<?php if(isset($_COOKIE['customColorTheme'])) echo e($customColorTheme = explode(',', $_COOKIE['customColorTheme'])[3]); ?>">
            </div>
            <div class="userSettings-col">
                <label for="theme-color-background-2">Upper background</label>
                <input type="color" name="theme-color-background-2" id="theme-color-background-2" value="<?php if(isset($_COOKIE['customColorTheme'])) echo e($customColorTheme = explode(',', $_COOKIE['customColorTheme'])[5]); ?>">
            </div>
            <div class="userSettings-col">
                <label for="theme-color-background-1">Lower background</label>
                <input type="color" name="theme-color-background-1" id="theme-color-background-1" value="<?php if(isset($_COOKIE['customColorTheme'])) echo e($customColorTheme = explode(',', $_COOKIE['customColorTheme'])[4]); ?>">
            </div>
        </div>
        <hr class="thin-line">
        <div class="userSettings-row">
            <input type="hidden" name="colorTheme" value="customTheme"></input>
            <input type="submit" value="Send" class="btn">
            </form>
        </div>  
    </article>
    <!-- ++++++++++ CHART COLOR-THEME ++++++++++ -->
    <!-- #TODO: Randomize Button -->
    <div class="userSettings-row">
        <h3 id="title_chartColorTheme">
            Create a custom chart color theme
            <input type='image' src='./img/arrow_right.png' alt='Arrow symbol' height='13px' width='13px'>
        </h3>
    </div>
    <article id="customChartColorForm" class="hide">
        <hr class="thin-line">
        <div class="userSettings-row">
            <span style="font-style: italic;">Set colors for the max. 10 different lines or sections of the charts.</span>
        </div>
        <form action="./?route=userSettings/adjustColorTheme" method="POST">
        <input type="hidden" name="customChartColor" value="1">
        <div class="userSettings-row">
            <div class="userSettings-col">
                <input type="color" name="color1" id="color1" value="<?php if(isset($_COOKIE['customChartColorTheme'])) echo e(explode(',', $_COOKIE['customChartColorTheme'])[0]); ?>">
            </div>
            <div class="userSettings-col">
                <input type="color" name="color2" id="color2" value="<?php if(isset($_COOKIE['customChartColorTheme'])) echo e(explode(',', $_COOKIE['customChartColorTheme'])[1]); ?>">
            </div>
            <div class="userSettings-col">
                <input type="color" name="color3" id="color3" value="<?php if(isset($_COOKIE['customChartColorTheme'])) echo e(explode(',', $_COOKIE['customChartColorTheme'])[2]); ?>">
            </div>
            <div class="userSettings-col">
                <input type="color" name="color4" id="color4" value="<?php if(isset($_COOKIE['customChartColorTheme'])) echo e(explode(',', $_COOKIE['customChartColorTheme'])[3]); ?>">
            </div>
            <div class="userSettings-col">
                <input type="color" name="color5" id="color5" value="<?php if(isset($_COOKIE['customChartColorTheme'])) echo e(explode(',', $_COOKIE['customChartColorTheme'])[4]); ?>">
            </div>
        </div>
        <div class="userSettings-row">
            <div class="userSettings-col">
                <input type="color" name="color6" id="color6" value="<?php if(isset($_COOKIE['customChartColorTheme'])) echo e(explode(',', $_COOKIE['customChartColorTheme'])[5]); ?>">
            </div>
            <div class="userSettings-col">
                <input type="color" name="color7" id="color7" value="<?php if(isset($_COOKIE['customChartColorTheme'])) echo e(explode(',', $_COOKIE['customChartColorTheme'])[6]); ?>">
            </div>
            <div class="userSettings-col">
                <input type="color" name="color8" id="color8" value="<?php if(isset($_COOKIE['customChartColorTheme'])) echo e(explode(',', $_COOKIE['customChartColorTheme'])[7]); ?>">
            </div>
            <div class="userSettings-col">
                <input type="color" name="color9" id="color9" value="<?php if(isset($_COOKIE['customChartColorTheme'])) echo e(explode(',', $_COOKIE['customChartColorTheme'])[8]); ?>">
            </div>
            <div class="userSettings-col">
                <input type="color" name="color10" id="color10" value="<?php if(isset($_COOKIE['customChartColorTheme'])) echo e(explode(',', $_COOKIE['customChartColorTheme'])[9]); ?>">
            </div>
        </div>
        <hr class="thin-line">
        <div class="userSettings-row">
            <input type="submit" value="Send" class="btn">
            </form>
        </div>
    </article>
    <!-- ++++++++++ Edit WEALTH DISTRIBUTIONS ++++++++++ -->
    <div class="userSettings-row">
        <h3 id="title_wdcats">
            Edit wealth distributions
            <input type='image' src='./img/arrow_right.png' alt='Arrow symbol' height='13px' width='13px'>
        </h3>
    </div>
    <article id="wdForm" class="hide">
        <hr class="thin-line">
        <form action="./?route=userSettings/changeUserData" method="POST" id="wdcatFormular">
        <?php for($i = 1; $i <= 18; $i+=2): ?>
            <div class="userSettings-row">
                <label for="wdcat<?php echo($i+1)/2; ?>">Category <?php echo ($i+1)/2; ?>: &nbsp &nbsp </label>
                <input type="hidden" name="oldwdcat<?php echo ($i+1)/2; ?>" id="oldwdcat<?php echo ($i+1)/2; ?>" value="<?php echo $userCats['wdcats'][$i-1]; ?>" form="wdcatFormular">
                <input type="text" name="wdcat<?php echo ($i+1)/2; ?>" id="wdcat<?php echo ($i+1)/2; ?>" value="<?php echo $userCats['wdcats'][$i-1]; ?>" form="wdcatFormular" maxlength="20">
                <input type="hidden" name="oldwdliq<?php echo ($i+1)/2; ?>" id="oldwdliq<?php echo ($i+1)/2; ?>" value="<?php echo $userCats['wdcats'][$i]; ?>" form="wdcatFormular">
                <input type="hidden" name="wdliq<?php echo ($i+1)/2; ?>" value="0">
                <input type="checkbox" name="wdliq<?php echo ($i+1)/2; ?>" id="wdliq<?php echo ($i+1)/2; ?>" value="1" <?php if($userCats['wdcats'][$i] === 1) echo "checked"; ?> form="wdcatFormular">
            </form>
                <form action="./?route=userSettings/changeUserData" method="post" id="deleteWDcatForm<?php echo ($i+1)/2; ?>">
                    <input type="hidden" name="deleteWDCat" value="1" form="deleteWDcatForm<?php echo ($i+1)/2; ?>">
                    <input type="hidden" name="deletewdcat<?php echo ($i+1)/2; ?>" value="<?php echo $userCats['wdcats'][$i-1]; ?>" form="deleteWDcatForm<?php echo ($i+1)/2; ?>">
                    <input type="hidden" name="deletewdliq<?php echo ($i+1)/2; ?>" value="<?php echo $userCats['wdcats'][$i]; ?>" form="deleteWDcatForm<?php echo ($i+1)/2; ?>">
                    <button class="btn-small btn-delete" id="dummyWDCatDelete<?php echo ($i+1)/2; ?>">X</button>
                <div class="error-container">
                    <div class="error-box hidden" id="disclaimerDeleteWDcat<?php echo ($i+1)/2; ?>">
                        <img src="./img/error.png" alt="Error Symbol" height="70px" width="70px">
                        <h1>Attention</h1>
                        <span>Do you really want to permanently delete "<?php echo $userCats['wdcats'][$i-1]; ?>"?</span>
                        <span>All entries of this category will be deleted aswell.</span><br>
                        <input type="submit" value="Yes" class="btn-small btn-delete" form="deleteWDcatForm<?php echo ($i+1)/2; ?>" id="deleteWDCat<?php echo ($i+1)/2; ?>">
                        <button class="btn-small btn-small-hover" id="abortWDCat<?php echo ($i+1)/2; ?>">No</button>
                    </div>
                </div>
                </form>
            </div>
        <?php endfor; ?>
        <div class="userSettings-row">
                <label for="wdcat10">Category 10: &nbsp</label>
                <input type="hidden" name="oldwdcat10" id="oldwdcat10" value="<?php echo $userCats['wdcats'][18]; ?>" form="wdcatFormular">
                <input type="text" name="wdcat10" id="wdcat10" value="<?php echo $userCats['wdcats'][18]; ?>" form="wdcatFormular" maxlength="20">
                <input type="hidden" name="oldwdliq10" id="oldwdliq10" value="<?php echo $userCats['wdcats'][19]; ?>" form="wdcatFormular">
                <input type="checkbox" name="wdliq10" id="wdliq10" value="1" <?php if($userCats['wdcats'][19] === 1) echo "ckecked"; ?> form="wdcatFormular">
            </form>
                <form action="./?route=userSettings/changeUserData" method="post" id="deleteWDcatForm10">
                    <input type="hidden" name="deleteWDCat" value="1" form="deleteWDcatForm10">
                    <input type="hidden" name="deletewdcat10" value="<?php echo $userCats['wdcats'][18]; ?>" form="deleteWDcatForm10">
                    <button class="btn-small btn-delete" id="dummyWDCatDelete10">X</button>
                <div class="error-container">
                    <div class="error-box hidden" id="disclaimerDeleteWDcat10">
                        <img src="./img/error.png" alt="Error Symbol" height="70px" width="70px">
                        <h1>Attention</h1>
                        <span>Do you really want to permanently delete "<?php echo $userCats['wdcats'][18]; ?>"?</span>
                        <span>All entries of this category will be deleted aswell.</span><br>
                        <input type="submit" value="Yes" class="btn-small btn-delete" form="deleteWDcatForm10" id="deleteWDCat10">
                        <button class="btn-small btn-small-hover" id="abortWDCat10">No</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <hr class="thin-line">
        <div class="userSettings-row">
        <input type="hidden" name="editWDCat" value="1" form="wdcatFormular">
            <input type="submit" value="Send" class="btn" id="dummyWDConfirm">
            <div class="error-container">
                <div class="error-box hidden" id="hintWD">
                    <img src="./img/error.png" alt="Error Symbol" height="70px" width="70px">
                    <h1>Attention</h1>
                    <span>If you edit a category to an empty string all entries of this category will be deleted permanently!</span>
                    <span>Do you want to confirm the edit?</span><br>
                    <input type="submit" value="Yes" class="btn-small btn-small-hover" form="wdcatFormular" id="confirmWDCat">
                    <button class="btn-small btn-delete" id="abortWDCat">No</button>  
                </div>
            </div>
            </form>
        </div>
    </article>
    <!-- ++++++++++ EDIT REVENUE CATEGORIES ++++++++++ -->
    <div class="userSettings-row">
        <h3 id="title_revcats">
            Edit revenue categories
            <input type='image' src='./img/arrow_right.png' alt='Arrow symbol' height='13px' width='13px'>
        </h3>
    </div>
    <article id="revcatForm" class="hide">
        <hr class="thin-line">
        <form action="./?route=userSettings/changeUserData" method="POST" id="revcatFormular">
        <?php for($i = 1; $i <= 9; $i++): ?>
            <div class="userSettings-row">
                <label for="revcat<?php echo $i; ?>">Category <?php echo $i; ?>: &nbsp &nbsp </label>
                <input type="hidden" name="oldrevcat<?php echo $i; ?>" id="oldrevcat<?php echo $i; ?>" value="<?php echo $userCats['revcats'][$i-1]; ?>" form="revcatFormular">
                <input type="text" name="revcat<?php echo $i; ?>" id="revcat<?php echo $i; ?>" value="<?php echo $userCats['revcats'][$i-1]; ?>" form="revcatFormular" maxlength="20">
                </form>
                <form action="./?route=userSettings/changeUserData" method="post" id="deleteRevcatForm<?php echo $i; ?>">
                    <input type="hidden" name="deleteRevCat" value="1" form="deleteRevcatForm<?php echo $i; ?>">
                    <input type="hidden" name="deleterevcat<?php echo $i; ?>" value="<?php echo $userCats['revcats'][$i-1]; ?>" form="deleteRevcatForm<?php echo $i; ?>">
                    <button class="btn-small btn-delete" id="dummyRevCatDelete<?php echo $i; ?>">X</button>
                <div class="error-container">
                    <div class="error-box hidden" id="disclaimerDeleteRevcat<?php echo $i; ?>">
                        <img src="./img/error.png" alt="Error Symbol" height="70px" width="70px">
                        <h1>Attention</h1>
                        <span>Do you really want to permanently delete "<?php echo $userCats['revcats'][$i-1]; ?>"?</span>
                        <span>All entries of this category will be deleted aswell.</span><br>
                        <input type="submit" value="Yes" class="btn-small btn-delete" form="deleteRevcatForm<?php echo $i; ?>" id="deleteRevCat<?php echo $i; ?>">
                        <button class="btn-small btn-small-hover" id="abortRevCat<?php echo $i; ?>">No</button>
                    </div>
                </div>
                </form>
            </div>
        <?php endfor; ?>
        <div class="userSettings-row">
                <label for="revcat10">Category 10: &nbsp </label>
                <input type="hidden" name="oldrevcat10" id="oldrevcat10" value="<?php echo $userCats['revcats'][9]; ?>" form="revcatFormular">
                <input type="text" name="revcat10" id="revcat10" value="<?php echo $userCats['revcats'][9]; ?>" form="revcatFormular" maxlength="20">
                <form action="./?route=userSettings/changeUserData" method="post" id="deleteRevcatForm10">
                    <input type="hidden" name="deleteRevCat" value="1" form="deleteRevcatForm10">
                    <input type="hidden" name="deleterevcat10" value="<?php echo $userCats['revcats'][9]; ?>" form="deleteRevcatForm10">
                    <button class="btn-small btn-delete" id="dummyRevCatDelete10">X</button>
                <div class="error-container">
                    <div class="error-box hidden" id="disclaimerDeleteRevcat10">
                        <img src="./img/error.png" alt="Error Symbol" height="70px" width="70px">
                        <h1>Attention</h1>
                        <span>Do you really want to permanently delete "<?php echo $userCats['revcats'][9]; ?>"?</span>
                        <span>All entries of this category will be deleted aswell.</span><br>
                        <input type="submit" value="Yes" class="btn-small btn-delete" form="deleteRevcatForm10" id="deleteRevCat10">
                        <button class="btn-small btn-small-hover" id="abortRevCat10">No</button>
                    </div>
                </div>
                </form>
            </div>
        <hr class="thin-line">
        <div class="userSettings-row">
            <input type="hidden" name="editRevCat" value="1" form="revcatFormular">
            <input type="submit" value="Send" class="btn" form="revcatFormular" id="dummyRevCatConfirm">
            <div class="error-container">
                <div class="error-box hidden" id="hintRevcat">
                    <img src="./img/error.png" alt="Error Symbol" height="70px" width="70px">
                    <h1>Attention</h1>
                    <span>If you edit a category to an empty string all entries of this category will be deleted permanently!</span>
                    <span>Do you want to confirm the edit?</span><br>
                    <input type="submit" value="Yes" class="btn-small btn-small-hover" form="revcatFormular" id="confirmRevCat">
                    <button class="btn-small btn-delete" id="abortRevCat">No</button>  
                </div>
            </div>
            </form>
        </div>
    </article>
    <!-- ++++++++++ EDIT EXPENDITURE CATEGORIES ++++++++++ -->
    <div class="userSettings-row">
        <h3 id="title_expcats">
            Edit expenditure categories
            <input type='image' src='./img/arrow_right.png' alt='Arrow symbol' height='13px' width='13px'>
        </h3>
    </div>
    <article id="expcatForm" class="hide">
        <hr class="thin-line">
        <form action="./?route=userSettings/changeUserData" method="POST" id="expcatFormular">
        <?php for($i = 1; $i < 10; $i++): ?>
            <div class="userSettings-row">
                <label for="expcat<?php echo $i; ?>">Category <?php echo $i; ?>: &nbsp &nbsp </label>
                <input type="hidden" name="oldexpcat<?php echo $i; ?>" id="oldexpcat<?php echo $i; ?>" value="<?php echo $userCats['expcats'][$i-1]; ?>" form="expcatFormular">
                <input type="text" name="expcat<?php echo $i; ?>" id="expcat<?php echo $i; ?>" value="<?php echo $userCats['expcats'][$i-1]; ?>" form="expcatFormular" maxlength="20">
                </form>
                <form action="./?route=userSettings/changeUserData" method="post" id="deleteExpcatForm<?php echo $i; ?>">
                    <input type="hidden" name="deleteExpCat" value="1" form="deleteExpcatForm<?php echo $i; ?>">
                    <input type="hidden" name="deleteexpcat<?php echo $i; ?>" value="<?php echo $userCats['expcats'][$i-1]; ?>" form="deleteExpcatForm<?php echo $i; ?>">
                    <button class="btn-small btn-delete" id="dummyExpCatDelete<?php echo $i; ?>">X</button>
                <div class="error-container">
                    <div class="error-box hidden" id="disclaimerDeleteExpcat<?php echo $i; ?>">
                        <img src="./img/error.png" alt="Error Symbol" height="70px" width="70px">
                        <h1>Do you really want to permanently delete <?php echo $userCats['expcats'][$i-1]; ?>?</h1>
                        <span>All entries of this category will be deleted aswell.</span><br>
                        <input type="submit" value="Yes" class="btn-small btn-delete" form="deleteExpcatForm<?php echo $i; ?>" id="deleteExpCat<?php echo $i; ?>">
                        <button class="btn-small btn-small-hover" id="abortExpCat<?php echo $i; ?>">No</button>
                    </div>
                </div>
                </form>
            </div>
        <?php endfor; ?>
        <div class="userSettings-row">
                <label for="expcat10">Category 10: &nbsp </label>
                <input type="hidden" name="oldexpcat10" id="oldexpcat10" value="<?php echo $userCats['expcats'][9]; ?>" form="expcatFormular">
                <input type="text" name="expcat10" id="expcat10" value="<?php echo $userCats['expcats'][9]; ?>" form="expcatFormular" maxlength="20">
                <form action="./?route=userSettings/changeUserData" method="post" id="deleteExpcatForm10">
                    <input type="hidden" name="deleteExpCat" value="1" form="deleteExpcatForm10">
                    <input type="hidden" name="deleteexpcat10" value="<?php echo $userCats['expcats'][9]; ?>" form="deleteExpcatForm10">
                    <button class="btn-small btn-delete" id="dummyExpCatDelete10">X</button>
                <div class="error-container">
                    <div class="error-box hidden" id="disclaimerDeleteExpcat10">
                        <img src="./img/error.png" alt="Error Symbol" height="70px" width="70px">
                        <h1>Do you really want to permanently delete <?php echo $userCats['expcats'][9]; ?>?</h1>
                        <span>All entries of this category will be deleted aswell.</span><br>
                        <input type="submit" value="Yes" class="btn-small btn-delete" form="deleteExpcatForm10" id="deleteExpCat10">
                        <button class="btn-small btn-small-hover" id="abortExpCat10">No</button>
                    </div>
                </div>
                </form>
            </div>
        <hr class="thin-line">
        <div class="userSettings-row">
            <input type="hidden" name="editExpCat" value="1" form="expcatFormular">
            <input type="submit" value="Send" class="btn" form="expcatFormular" id="dummyExpCatConfirm">
            <div class="error-container">
                <div class="error-box hidden" id="hintExpcat">
                    <img src="./img/error.png" alt="Error Symbol" height="70px" width="70px">
                    <h1>Attention</h1>
                    <span>If you edit a category to an empty string all entries of this category will be deleted permanently!</span>
                    <span>Do you want to confirm the edit?</span><br>
                    <input type="submit" value="Yes" class="btn-small btn-small-hover" form="expcatFormular" id="confirmExpCat">
                    <button class="btn-small btn-delete" id="abortExpCat">No</button>  
                </div>
            </div>
            </form>
        </div>
    </article>
    <div class="userSettings-row">
        <a href="./?route=homepage" class="cancel-btn">Get Back!</a>
    </div>
    
</section>