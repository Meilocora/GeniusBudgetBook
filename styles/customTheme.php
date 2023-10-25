<?php header("Content-type: text/css"); ?>
<?php $colorSet = explode(",", $_COOKIE['customColorTheme']); ?>
:root {
    --theme-color-light:    <?php echo $colorSet[0]; ?>;
    --theme-color-medium:   <?php echo $colorSet[1]; ?>;
    --theme-color-heavy:    <?php echo $colorSet[2]; ?>;
    --theme-color-neon:     <?php echo $colorSet[3]; ?>;
    --theme-color-background: linear-gradient(0deg, <?php echo $colorSet[4]; ?> 0%, <?php echo $colorSet[5]; ?> 100%);
}