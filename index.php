<?php
ini_set('memory_limit', '512000000');

require_once __DIR__ . '/inc/all.php';

// ++++++++++ CONTAINER INITIALISATION ++++++++++\\
$container = new \App\Support\Container();
$container->add('pdo', function() {
    return require __DIR__ . '/inc/db-connect.inc.php';
});
$container->add('routingController', function() use($container) {
    return new \App\Controller\RoutingController();
});
$container->add('colorThemeController', function() {
    return new \App\Controller\ColorThemeController();
});

$container->add('dbController', function() use($container) {
    return new \App\Controller\DBController(
        $container->get('dbInitialize'),
        $container->get('dbQuery'),
        $container->get('loginController'),
        $container->get('yearlyController'),
        $container->get('usersController'));
});
$container->add('dbInitialize', function() use($container) {
    return new \App\DBInitialize\DBInitialize(
        $container->get('pdo'),
        $container->get('dbQuery'),
        $container->get('usersRepository'),
        $container->get('wdRepository'),
        $container->get('entryRepository'),
        $container->get('yearlyController'));
});
$container->add('dbQuery', function() use($container) {
    return new \App\DBInitialize\DBQuery(
        $container->get('pdo'));
});

$container->add('loginController', function() use($container) {
    return new \App\Controller\LoginController(
        $container->get('authService'));
});
$container->add('authServiceUser', function() {
    return new \App\AuthService\AuthServiceUser();
});
$container->add('authService', function() use($container) {
    return new \App\AuthService\AuthService(
        $container->get('pdo'),
        $container->get('usersRepository'));
});

$container->add('entryController', function() use($container) {
    return new \App\Controller\EntryController(
        $container->get('entryModel'),
        $container->get('entryRepository'),
        $container->get('monthlyPageWizard'),
        $container->get('dbController'),
        $container->get('wdController'),
        $container->get('usersController'));
});
$container->add('entryModel', function() {
    return new \App\Entry\EntryModel();
});
$container->add('entryRepository', function() use($container) {
    return new \App\Entry\EntryRepository(
        $container->get('pdo'));
});

$container->add('wdController', function() use($container) {
    return new \App\Controller\WDController(
        $container->get('wdRepository'),
        $container->get('usersRepository'));
});
$container->add('wdRepository', function() use($container) {
    return new \App\WealthDistribution\WDRepository(
        $container->get('pdo'),
        $container->get('usersRepository'));
});

$container->add('usersController', function() use($container) {
    return new App\Controller\UsersController(
        $container->get('usersRepository'),
        $container->get('wdRepository'),
        $container->get('entryRepository'),
        $container->get('yearlyController'),
        $container->get('yearlyRepository'));
});
$container->add('usersRepository', function() use($container) {
    return new \App\Users\UsersRepository(
        $container->get('pdo'),
        $container->get('authServiceUser'));
});

$container->add('yearlyController', function() use($container) {
    return new \App\Controller\YearlyController(
        $container->get('yearlyRepository'));
});
$container->add('yearlyRepository', function() use($container) {
    return new \App\Yearly\YearlyRepository(
        $container->get('pdo'));
});

$container->add('monthlyPageWizard', function() {
    return new \App\FrontendWizard\MonthlyPageWizard();
});

$container->add('homepageController', function() use($container) {
    return new \App\Controller\HomepageController(
        $container->get('wdController'),
        $container->get('yearlyController'),
        $container->get('entryController'),
        $container->get('colorThemeController'),
        $container->get('chartController'));
});

$container->add('chartController', function() use($container){
    return new \App\Controller\ChartController(
        $container->get('wdController'),
        $container->get('yearlyController'),
        $container->get('entryController'),
        $container->get('entryRepository'),
        $container->get('colorThemeController'),
        $container->get('usersController'));
});

$container->add('overviewController', function() use($container) {
    return new \App\Controller\OverviewController(
        $container->get('entryController'),
        $container->get('colorThemeController'),
        $container->get('chartController'));
});

$container->add('customOverviewController', function() use($container) {
    return new \App\Controller\CustomOverviewController(
        $container->get('entryController'),
        $container->get('entryRepository'),
        $container->get('usersController'));
});


// ++++++++++ GLOBAL VARIABLES ++++++++++\\
$navRoutes = [
    'homepage',
    'overview',
    'monthly-page',
    'custom-overview',
    'tools'
];

// Unset Custom Colorsets fast
// session_start();
// var_dump($_POST);
// var_dump($_SESSION);
// session_destroy();
// setcookie('customColorTheme', '', -1);
// setcookie('customChartColorTheme', '', -1);

if(isset($_POST['colorTheme'])) {
    setcookie('colorTheme', $_POST['colorTheme']);
    $colorTheme = $_POST['colorTheme'];
} else {
    if(isset($_COOKIE['colorTheme'])) {
        $colorTheme = $_COOKIE['colorTheme'];
    } else {
        $colorTheme = 'greenTheme';
        setcookie('colorTheme', $colorTheme);
    }
}
session_start();
if(isset($_POST['chartColorSet'])) {
    setcookie('chartColorSet', $_POST['chartColorSet']);
    $chartColorSet = $_POST['chartColorSet'];
} else {
    if(isset($_COOKIE['chartColorSet'])) {
        $chartColorSet = $_COOKIE['chartColorSet'];
    } else {
        $chartColorSet = 'default';
    }
}
if(isset($_POST['timeInterval'])) {
    $_SESSION['timeInterval'] = $_POST['timeInterval'];
    $timeInterval = $_POST['timeInterval'];
} else {
    if(isset($_SESSION['timeInterval'])) {
        $timeInterval = $_SESSION['timeInterval'];
    } else {
        $timeInterval = 'YTD';
    }
}
// ----------------------------------------------------------------------------------
if(isset($_POST['year'])) {
    $_SESSION['year'] = $_POST['year'];
    $year = $_POST['year'];
    $timeInterval = 'YTD';
    $_SESSION['customStartMonth'] = null;
    $_SESSION['customEndMonth'] = null;
    $customStartMonth = null;
    $customEndMonth = null;
} else {
    if(isset($_SESSION['year'])) {
        $year = $_SESSION['year'];
    } else {
        $year = date('Y');
    }
}
if($timeInterval == 'Custom') { 
    if(isset($_POST['customStartMonth']) & isset($_POST['customEndMonth'])) {
        $_SESSION['customStartMonth'] = $_POST['customStartMonth'];
        $_SESSION['customEndMonth'] = $_POST['customEndMonth'];
    }
} else {
    $_SESSION['customStartMonth'] = null;
    $_SESSION['customEndMonth'] = null;
    $customStartMonth = null;
    $customEndMonth = null;
}

if($_SESSION['customStartMonth'] !== null & $_SESSION['customEndMonth'] !== null) {
    if(strtotime($_SESSION['customStartMonth']) < strtotime($_SESSION['customEndMonth'])) {
        $customStartMonth = $_SESSION['customStartMonth'];
        $customEndMonth = $_SESSION['customEndMonth'];
    } elseif (strtotime($_SESSION['customStartMonth']) > strtotime($_SESSION['customEndMonth'])) {
        $_SESSION['customStartMonth'] = null;
        $_SESSION['customEndMonth'] = null;
        $customStartMonth = null;
        $customEndMonth = null;
    }
} elseif($_SESSION['customStartMonth'] === null & $_SESSION['customEndMonth'] === null) {
    $customStartMonth = null;
    $customEndMonth = null;
}
// ----------------------------------------------------------------------------------
if(isset($_POST['barchartScale'])) {
    $_SESSION['barchartScale'] = $_POST['barchartScale'];
    $barchartScale = $_POST['barchartScale'];
} else {
    if(isset($_SESSION['barchartScale'])) {
        $barchartScale = $_SESSION['barchartScale'];
    } else {
        $barchartScale = 'linear';
    }
}

// ++++++++++ ROUTING LOGIC ++++++++++\\
$route = @(string) ($_GET['route'] ?? 'page');

$authService = $container->get('authService');
$authService->ensureSession();
if(isset($_SESSION['username'])) {
    $userShortcut = strtoupper(substr($_SESSION['username'],0,1));
} else {
    $userShortcut = '';
}

if($route === 'page') {
    $routingController = $container->get('routingController');
    $routingController->render('start/start', [
        'navRoutes' => $navRoutes,
        'colorTheme' => $colorTheme,
        'userShortcut' => $userShortcut
    ]);
}
elseif($route === 'login') {
    $routingController = $container->get('routingController');
    $routingController->render('start/login', [
        'navRoutes' => $navRoutes,
        'colorTheme' => $colorTheme,
        'userShortcut' => $userShortcut
    ]);
}
else if($route === 'logout') {
    $loginController = $container->get('loginController');
    $loginController->logout(); 
}
elseif($route === 'login/verify') {
    $loginController = $container->get('loginController');
    $loginController->login();
}
elseif($route === 'register') {
    $dbController = $container->get('dbController');
    $dbController->usersInitialize();
    $usersController = $container->get('usersController');
    $usernames = implode(',',$usersController->fetchUsernames());
    setcookie('name', "{$usernames}", time() + 1);
    $routingController = $container->get('routingController');
    $routingController->render('start/register', [
        'navRoutes' => $navRoutes,
        'colorTheme' => $colorTheme,
        'userShortcut' => $userShortcut,
        'usernames' =>$usernames
    ]);
}
elseif($route === 'register/newUser') {
    $dbController = $container->get('dbController');
    $addUserOk = $dbController->addUser();
    if($addUserOk === true) {
        $_POST['username'] = $_POST['newUsername'];
        $_POST['password'] = $_POST['newUserPw']; 
        $loginController = $container->get('loginController');
        $loginController->login();
    }
    else {
        $_POST['errorMessage'] = $addUserOk;
        $routingController = $container->get('routingController');
        $routingController->render('start/register', [
            'navRoutes' => $navRoutes,
            'colorTheme' => $colorTheme,
            'userShortcut' => $userShortcut
        ]);
    }
}
elseif($route === 'homepage') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $homepageController = $container->get('homepageController');
    $homepageController->showHomepage($navRoutes, $colorTheme, $userShortcut, $year, $customStartMonth, $customEndMonth, $timeInterval, $chartColorSet);
}
elseif($route === 'homepage/adjustgoals') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $yearlyController = $container->get('yearlyController');
    $yearlyController->setGoals();
    header('Location: ./?route=homepage');
}
elseif($route === 'homepage/sandbox') {   
    $dbController = $container->get('dbController');
    $dbController->sandboxInitialize();
}
elseif($route === 'userSettings') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    isset($_SESSION['errorArray']) ? $errorArray = explode(',',$_SESSION['errorArray']) : $errorArray = [];
    if(isset($_SESSION['errorArray'])) unset($_SESSION['errorArray']);
    $usersController = $container->get('usersController');
    $userCats = $usersController->fetchUserCats();
    $routingController = $container->get('routingController');
    $routingController->render('userSettings', [
        'navRoutes' => $navRoutes,
        'colorTheme' => $colorTheme,
        'userShortcut' => $userShortcut,
        'errorArray' => $errorArray,
        'userCats' => $userCats
    ]);
}
elseif($route === 'userSettings/adjustColorTheme') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $colorThemeController = $container->get('colorThemeController');
    $colorThemeController->adjustColorTheme();
}
elseif($route === 'userSettings/changeUserData') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $usersController = $container->get('usersController');
    $usersController->changeUserData();
}
elseif($route === 'overview') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $overviewController = $container->get('overviewController');
    $overviewController->showOverview($navRoutes, $colorTheme, $userShortcut, $year, $customStartMonth, $customEndMonth, $timeInterval, $barchartScale);
}
elseif($route === 'monthly-page') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    if(isset($_GET['page'])) {
        $_SESSION['page'] = $_GET['page'];
        $currentPage = max(1, @(int) ($_GET['page']));
    } else {
        if(isset($_SESSION['page'])) {
            $currentPage = (int) $_SESSION['page'];
        } else {
            $currentPage = 1;
        }
    }
    if(isset($_POST['perPage'])) {
        $_SESSION['perPage'] = $_POST['perPage'];
        $perPage = @(int) ($_POST['perPage']);
        $currentPage = 1;
    } else {
        if(isset($_SESSION['perPage'])) {
            $perPage = $_SESSION['perPage'];
        } else {
            $perPage = 4;
        }
    }
    if(isset($_POST['sortingProperty'])) {
        $_SESSION['sortingProperty'] = $_POST['sortingProperty'];
        $sortingProperty = @(string) ($_POST['sortingProperty']);
    } else {
        if(isset($_SESSION['sortingProperty'])) {
            $sortingProperty = $_SESSION['sortingProperty'];
        } else {
            $sortingProperty = 'dateslug';
        }
    }
    if(isset($_POST['sort'])) {
        $_SESSION['sort'] = $_POST['sort'];
        $sort = @(string) ($_POST['sort']);
    } else {
        if(isset($_SESSION['sort'])) {
            $sort = $_SESSION['sort'];
        } else {
            $sort = 'sortDateAsc';
        }
    }
    if(isset($_POST['month_year'])) {
        $_SESSION['date'] = $_POST['month_year'];
        $date = $_POST['month_year'];
    } else {
        if(isset($_SESSION['date'])) {
            $date = $_SESSION['date'];
        } else {
            $date = date('Y-m');
        }
    }
    $entryController = $container->get('entryController');
    $entryController->showEntries($navRoutes, $colorTheme, $userShortcut, $sortingProperty, $sort, $date, $perPage, $currentPage);
}
else if($route === 'monthly-page/create') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $entryController = $container->get('entryController');
    $entryController->create(); 
}
else if($route === 'monthly-page/delete') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $entryController = $container->get('entryController');
    $id = @(int) ($_POST['id'] ?? 0);
    $entryController->delete($id); 
}
else if($route === 'monthly-page/update') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $entryController = $container->get('entryController');
    $id = @(int) ($_POST['id'] ?? 0);
    $entryController->update($id); 
}
else if($route === 'monthly-page/adjustwd') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $wdController = $container->get('wdController');
    $wdController->updateBalances();
}
else if($route === 'monthly-page/fixedentries') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $date = $_POST['date'];
    $entryController = $container->get('entryController');
    $entryController->transferFixedEntries($date);
}
else if($route === 'custom-overview') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    if(isset($_GET['page'])) {
        $_SESSION['cPage'] = $_GET['page'];
        $currentPage = max(1, @(int) ($_GET['page']));
    } else {
        if(isset($_SESSION['cPage'])) {
            $currentPage = (int) $_SESSION['cPage'];
        } else {
            $currentPage = 1;
        }
    }
    if(isset($_POST['perPage'])) {
        $_SESSION['cPerPage'] = $_POST['perPage'];
        $perPage = @(int) ($_POST['perPage']);
        $currentPage = 1;
    } else {
        if(isset($_SESSION['cPerPage'])) {
            $perPage = $_SESSION['cPerPage'];
        } else {
            $perPage = 5;
        }
    }
    if(isset($_POST['sortingProperty'])) {
        $_SESSION['cSortingProperty'] = $_POST['sortingProperty'];
        $cSortingProperty = @(string) ($_POST['sortingProperty']);
    } else {
        if(isset($_SESSION['cSortingProperty'])) {
            $cSortingProperty = $_SESSION['cSortingProperty'];
        } else {
            $cSortingProperty = 'dateslug';
        }
    }
    if(isset($_POST['sort'])) {
        $_SESSION['cSort'] = $_POST['sort'];
        $cSort = @(string) ($_POST['sort']);
    } else {
        if(isset($_SESSION['cSort'])) {
            $cSort = $_SESSION['cSort'];
        } else {
            $cSort = 'sortDateAsc';
        }
    }
    if(isset($_POST['cTimeinterval'])) {
        $_SESSION['cTimeinterval'] = $_POST['cTimeinterval'];
        $cTimeinterval = $_POST['cTimeinterval'];
    } else {
        if(isset($_SESSION['cTimeinterval'])) {
            $cTimeinterval = $_SESSION['cTimeinterval'];
        } else {
            $cTimeinterval = 'YTD';
        }
    }
    if($cTimeinterval === 'Custom') {
        if(isset($_POST['cStartDate']) & isset($_POST['cEndDate'])) {
            $cStartDate = $_POST['cStartDate'];
            $cEndDate = $_POST['cEndDate'];
            $_SESSION['cStartDate'] = $_POST['cStartDate'];
            $_SESSION['cEndDate'] = $_POST['cEndDate'];
        } elseif(isset($_SESSION['cStartDate']) & isset($_SESSION['cEndDate'])) {
            $cStartDate = $_SESSION['cStartDate'];
            $cEndDate = $_SESSION['cEndDate'];
        } else {
            $cStartDate = null;
            $cEndDate = null;
        }
    } else {
        $cStartDate = null;
        $cEndDate = null;
    }
    if(isset($_POST['cEntryType'])) {
        $_SESSION['cEntryType'] = $_POST['cEntryType'];
        $cEntryType = $_POST['cEntryType'];
    } else {
        if(isset($_SESSION['cEntryType'])) {
            $cEntryType = $_SESSION['cEntryType'];
        } else {
            $cEntryType = 'AllTypes';
        }
    }
    if(isset($_POST['cFixation'])) {
        $_SESSION['cFixation'] = $_POST['cFixation'];
        $cFixation = $_POST['cFixation'];
    } else {
        if(isset($_SESSION['cFixation'])) {
            $cFixation = $_SESSION['cFixation'];
        } else {
            $cFixation = 'AllFixations';
        }
    }
    if(isset($_POST['cCategories'])) {
        $_SESSION['cCategories'] = $_POST['cCategories'];
        $cCategories = $_POST['cCategories'];
    } else {
        if(isset($_SESSION['cCategories'])) {
            $cCategories = $_SESSION['cCategories'];
        } else {
            $cCategories = 'allCategories';
        }
    }
    if($cCategories === 'certainCategory') {
        if(isset($_POST['cCategoryQuery'])) {
            $_SESSION['cCategoryQuery'] = $_POST['cCategoryQuery'];
            $cCategoryQuery = $_POST['cCategoryQuery'];
        } else {
            $cCategoryQuery = $_SESSION['cCategoryQuery'];
        }
    } else {
        $cCategoryQuery = null;
    }
    if(isset($_POST['cTitles'])) {
        $_SESSION['cTitles'] = $_POST['cTitles'];
        $cTitles = $_POST['cTitles'];
    } else {
        if(isset($_SESSION['cTitles'])) {
            $cTitles = $_SESSION['cTitles'];
        } else {
            $cTitles = 'allTitles';
        }
    }
    if($cTitles === 'certainTitle') {
        if(isset($_POST['cTitleQuery'])) {
            $_SESSION['cTitleQuery'] = $_POST['cTitleQuery'];
            $cTitleQuery = $_POST['cTitleQuery'];
        } else {
            $cTitleQuery = $_SESSION['cTitleQuery'];
        }
    } else {
        $cTitleQuery = null;
    }
    if(isset($_POST['cAmounts'])) {
        $_SESSION['cAmounts'] = $_POST['cAmounts'];
        $cAmounts = $_POST['cAmounts'];
    } else {
        if(isset($_SESSION['cAmounts'])) {
            $cAmounts = $_SESSION['cAmounts'];
        } else {
            $cAmounts = 'allAmounts';
        }
    }
    if($cAmounts === 'Custom') {
        if(isset($_POST['fromAmount']) & isset($_POST['toAmount'])) {
            $_SESSION['fromAmount'] = $_POST['fromAmount'];
            $fromAmount = $_POST['fromAmount'];
            $_SESSION['toAmount'] = $_POST['toAmount'];
            $toAmount = $_POST['toAmount'];
        } else {
            $fromAmount = $_SESSION['fromAmount'];
            $toAmount = $_SESSION['toAmount'];
        }
    } else {
        $fromAmount = null;
        $toAmount = null;
    }
    if(isset($_POST['cComments'])) {
        $_SESSION['cComments'] = $_POST['cComments'];
        $cComments = $_POST['cComments'];
    } else {
        if(isset($_SESSION['cComments'])) {
            $cComments = $_SESSION['cComments'];
        } else {
            $cComments = 'allComments';
        }
    }
    if($cComments === 'certainComment') {
        if(isset($_POST['cCommentQuery'])) {
            $_SESSION['cCommentQuery'] = $_POST['cCommentQuery'];
            $cCommentQuery = $_POST['cCommentQuery'];
        } else {
            $cCommentQuery = $_SESSION['cCommentQuery'];
        }
        
    } else {
        $cCommentQuery = null;
    }
    $customOverviewController = $container->get('customOverviewController');
    $customOverviewController->showCustomOverview($navRoutes, $colorTheme, $userShortcut, $cTimeinterval, $cStartDate, $cEndDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cTitles, $cTitleQuery, $cAmounts, $fromAmount, $toAmount, $cComments, $cCommentQuery, $cSortingProperty, $cSort, $currentPage, $perPage);
}
else if($route === 'tools') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $routingController = $container->get('routingController');
    $routingController->render('tools', [
        'navRoutes' => $navRoutes,
        'colorTheme' => $colorTheme,
        'userShortcut' => $userShortcut
    ]);
}
else {
    $routingController = $container->get('routingController');
    $routingController->showError404($navRoutes, $colorTheme, $userShortcut);
}
