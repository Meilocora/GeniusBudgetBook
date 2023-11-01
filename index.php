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
        $container->get('colorThemeController'));
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
    if(isset($_POST['year'])) {
        $_SESSION['year'] = $_POST['year'];
        $year = $_POST['year'];
    } else {
        if(isset($_SESSION['year'])) {
            $year = $_SESSION['year'];
        } else {
            $year = date('Y');
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
    $homepageController = $container->get('homepageController');
    $homepageController->showHomepage($navRoutes, $colorTheme, $userShortcut, $year, $timeInterval, $chartColorSet);
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
    $routingController = $container->get('routingController');
    $routingController->render('budget-book/overview', [
        'navRoutes' => $navRoutes,
        'colorTheme' => $colorTheme,
        'userShortcut' => $userShortcut
    ]);
}
elseif($route === 'monthly-page') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    if(isset($_GET['page'])) {
        $_SESSION['page'] = $_GET['page'];
        $currentPage = max(1, @(int) ($_GET['page']));
    } else {
        if(isset($_SESSION['page'])) {
            $currentPage = $_SESSION['page'];
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
    #TODO: Pagination anpassen, sodass max. 5 Zahlen angezeigt werden, der Rest versteckt und mit Pfeilsymbolen navigierbar
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
    $sortingProperty = @(string) ($_POST['sortingProperty'] ?? 'dateslug');
    $sort = @(string) ($_POST['sort'] ?? 'sortDateAsc');
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
    $routingController = $container->get('routingController');
    $routingController->render('budget-book/custom-overview', [
        'navRoutes' => $navRoutes,
        'colorTheme' => $colorTheme,
        'userShortcut' => $userShortcut
    ]);
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
