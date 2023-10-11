<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Genius Budget Book</title>
</head>
<body> 
    <header class="header-container">
        <a href="./?route=logout">
            <span id="header-banner-name">Genius Budget Book</span>
        </a> 
        <?php if(!empty($_GET['route']) && in_array($_GET['route'],$navRoutes)): ?>
            <div class="clearfix">
                <nav class="nav-container">
                    <ul>
                        <li class="<?php if($_GET['route'] === 'homepage') echo 'active'; ?>">
                            <a href="./?route=homepage" class="nav-element" >Homepage</a>
                        </li>
                        <li class="dropdown <?php if($_GET['route'] === 'overview' | $_GET['route'] === 'monthly-page' | $_GET['route'] === 'custom-overview') echo 'active'; ?>">
                            <span>Budget-Book</span>
                            <div class="clearfix" id="nav-content">
                                <a href="./?route=overview" class="nav-element <?php if($_GET['route'] === 'overview') echo 'active_sub'; ?>">Overview</a>
                                <a href="./?route=monthly-page" class="nav-element <?php if($_GET['route'] === 'monthly-page') echo 'active_sub'; ?>">Monthly Page</a>
                                <a href="./?route=custom-view" class="nav-element <?php if($_GET['route'] === 'custom-view') echo 'active_sub'; ?>">Custom Overview</a>     
                            </div>
                        </li>
                        <li class="<?php if($_GET['route'] === 'tools') echo 'active'; ?>">
                            <a href="./?route=tools" class="nav-element">Tools</a> 
                        </li>
                        <li>
                            <a href="./?route=logout" class="logout-btn">Logout</a>   
                        </li>
                    </ul>
                </nav>
            </div>    
        <?php endif; ?> 
    </header>
        <?php echo $content; ?>
    <footer class="footer">
        <p>Projekt: Genius Budget Book von Eric Manig</p>
    </footer>
</body>

</html>