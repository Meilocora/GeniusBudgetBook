<?php

namespace App\AuthService;

use PDO;
use App\Users\UsersRepository;

class AuthService {

    public function __construct(
        protected PDO $pdo,
        protected UsersRepository $usersRepository) {}

    public function ensureSession() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function ensureLogin() {
        $this->ensureSession();
        if(isset($_SESSION['username'])) {
            $username = (string) $_SESSION['username'];
            $userExisting = $this->usersRepository->userExisting($username);
            if(!empty($userExisting)) {
                // No SESSION and user is not in database
                return;
            }
        }
        // User not logged in
        header("Location: ./?route=page");
        die();  // Important! Otherwise the rest of index.php will still be executed!
    }

    public function handleLogin(string $username, string $password): bool {
        $stmt = $this->pdo->prepare('SELECT * FROM `users` WHERE `username` = :username');
        $stmt->bindValue(':username', $username);
        $stmt->setFetchMode(PDO::FETCH_CLASS, AuthServiceUser::class);
        $stmt->execute();
        $user = $stmt->fetch();
        $passwordOk = password_verify($password, $user->password);
        if($passwordOk === true) {
            $this->ensureSession();
            session_regenerate_id();
            $_SESSION['username'] = $username;
            return true;
        }
        else{
            return false;
        }
    }

    public function logout() {
        $this->ensureSession();
        session_destroy();
    }

}