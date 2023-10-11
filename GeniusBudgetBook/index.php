<?php

require_once __DIR__ . '/inc/all.php';

// Initialize and fill the container with class receipts
$container = new \App\Support\Container();
$container->add('pdo', function() {
    return require __DIR__ . '/inc/db-connect.inc.php';
});
$container->add('routingController', function() use($container) {
    return new \App\Controller\RoutingController();
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
$container->add('authService', function() use($container) {
    return new \App\AuthService\AuthService(
        $container->get('pdo'));
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
        $container->get('yearlyController'));
});
$container->add('usersRepository', function() use($container) {
    return new \App\Users\UsersRepository(
        $container->get('pdo'));
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

// Routing Logic
$route = @(string) ($_GET['route'] ?? 'page');

$navRoutes = [
    'homepage',
    'overview',
    'monthly-page',
    'tools'
];

if($route === 'page') {
    $routingController = $container->get('routingController');
    $routingController->render('start/start', [
        'navRoutes' => $navRoutes
    ]);
}
elseif($route === 'login') {
    $routingController = $container->get('routingController');
    $routingController->render('start/login', [
        'navRoutes' => $navRoutes
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
    $routingController = $container->get('routingController');
    $routingController->render('start/register', [
        'navRoutes' => $navRoutes
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
            'navRoutes' => $navRoutes
        ]);
    }
}
elseif($route === 'homepage') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $routingController = $container->get('routingController');
    $routingController->render('homepage', [
        'navRoutes' => $navRoutes
    ]);
}
elseif($route === 'homepage/sandbox') {   
    $dbController = $container->get('dbController');
    $dbController->sandboxInitialize();
}
elseif($route === 'overview') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $routingController = $container->get('routingController');
    $routingController->render('budget-book/overview', [
        'navRoutes' => $navRoutes
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
    $entryController->showEntries($navRoutes, $sortingProperty, $sort, $date, $perPage, $currentPage, $_SESSION['username']);
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
else if($route === 'custom-view') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $routingController = $container->get('routingController');
    $routingController->render('custom-view', [
        'navRoutes' => $navRoutes
    ]);
}
else if($route === 'tools') {
    $authService = $container->get('authService');
    $authService->ensureLogin();
    $routingController = $container->get('routingController');
    $routingController->render('tools', [
        'navRoutes' => $navRoutes
    ]);
}
#TODO: route = settings (von homepage ansteuerbar)
// => Categorien ändern oder löschen
// => Username bzw. Passwort ändern
#TODO: route = admin (bei Login)
// => user löschen bzw. verändern
else {
    $routingController = $container->get('routingController');
    $routingController->showError404($navRoutes);
}
