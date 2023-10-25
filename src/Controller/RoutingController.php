<?php 

namespace App\Controller;

class RoutingController extends AbstractController{

    public function showError404($navRoutes, $colorTheme, $userShortcut) {
        http_response_code(404);
        $this->render("error/showError404", [
            'navRoutes' => $navRoutes,
            'colorTheme' => $colorTheme,
            'userShortcut' => $userShortcut
        ]);
    }
}