<?php 

namespace App\Controller;

use App\Controller\AbstractController;
use App\AuthService\AuthService;

class LoginController extends AbstractController {

    public function __construct(protected AuthService $authService) {}

    public function login() {
        if(!empty($_POST)) {
            $username = @(string) ($_POST['username'] ?? '');
            $password = @(string) ($_POST['password'] ?? '');
            if(!empty($username) && !empty($password)) {
                $loginOk = $this->authService->handleLogin($username, $password);
                if($loginOk) {
                    header("Location: ./?route=homepage");
                    return;
                }
            }
            header("Location: ./?route=login");
            return;
        }
        else {
            header("Location: ./?route=login");
        }
    }

    public function logout() {
        $this->authService->logout();
        header("Location: ./?route=page");
    }
}