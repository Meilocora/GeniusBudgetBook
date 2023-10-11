<?php 

namespace App\DBInitialize;

use PDO;

class DBQuery {
    public function __construct(protected PDO $pdo) {}

    public function databaseExisting(): bool {
        $stmt = $this->pdo->prepare("SHOW DATABASES LIKE 'geniusbudgetbook'");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function sandboxExisting(): bool {
        $stmt = $this->pdo->prepare("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'geniusbudgetbook' AND TABLE_NAME = 'sandboxuser'");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function usersExisting() {
        $stmt = $this->pdo->prepare("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'geniusbudgetbook' AND TABLE_NAME = 'users'");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)) {
            return true;
        }
        else{
            return false;
        }
    }
}