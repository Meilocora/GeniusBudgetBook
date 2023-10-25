<section class="userSettings-container">
    <div class="userSettings-row">
        <h1>Adjust general settings</h1>
    </div>
    <div class="userSettings-row">
        <h3>Welcome <?php echo e($_SESSION['username']); ?> !</h3>
    </div>
    <div class="userSettings-row">
        <form action="./?route=userSettings/adjustColorTheme" method="POST" id="standardColorForm">
            <label for="colorTheme">Change color theme: </label>
            <select name="colorTheme" id="colorTheme">
                <option value="greenTheme">Green theme</option>
                <option value="blueTheme">Blue theme</option>
                <option value="redTheme">Red theme</option>
                <?php if(isset($_COOKIE['customColorTheme'])): ?>
                    <option value="customTheme">Custom theme</option>
                <?php endif; ?>
            </select>   
            <input type='image' src='./img/checkmark.png' alt='Checkmark symbol' height='20px' width='20px'>
        </form>
    </div>

    <div class="userSettings-row">
        <form action="./?route=userSettings/adjustColorTheme" method="POST" id="customColorForm">
            <h3>Create a custom color theme</h3>
            <input type="hidden" name="customColorTheme" value="1">
    </div>
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
            <label for="theme-color-heavy">Heavy color </label>
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
    <div class="userSettings-row">
        <input type="submit" value="Send">
        </form>
    </div>

    <div class="userSettings-row">
        <form action="./?route=userSettings/adjustColorTheme" method="POST" id="customChartColorForm">
            <h3>Create a custom chart color theme</h3>
            <input type="hidden" name="customChartColor" value="1">
    </div>
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
    <div class="userSettings-row">
        <input type="submit" value="Send">
        </form>
    </div>

    <div class="userSettings-row">
        <a href="./?route=homepage">Get Back!</a>
    </div>
</section>