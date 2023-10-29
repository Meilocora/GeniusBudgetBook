<?php 

namespace App\Users;

use PDO;
use App\AuthService\AuthServiceUser;

class UsersRepository {
    public function __construct(
        protected PDO $pdo,
        protected AuthServiceUser $authServiceUser) {}

    public function fetchAll($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `username` = :username");
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchAllData() {
        $stmt = $this->pdo->prepare("SELECT * FROM `users`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function userExisting($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `username` = :username");
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchUser($username) {
        $stmt = $this->pdo->prepare('SELECT * FROM `users` WHERE `username` = :username');
        $stmt->bindValue(':username', $username);
        $stmt->setFetchMode(PDO::FETCH_CLASS, AuthServiceUser::class);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function fetchWDCategories($username): array {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `username` = :username");
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $filteredResults = [];
        foreach($results AS $result) {
            foreach($result AS $key => $value) {
                if(preg_match('/^wd{1,2}\d$/', $key) && $value != '') {
                    $filteredResults[$key] = $value;
                    $filteredResults[$key . 'Liquid'] = $result[$key . 'Liquid'];
                }
            }
        }
        return $filteredResults;
    }

    public function generateTable() {
        $stmt = $this->pdo->prepare('CREATE TABLE `geniusbudgetbook`.`users` (
                                `id` INT NOT NULL AUTO_INCREMENT,
                                `username` VARCHAR(30) NOT NULL, 
                                `password` VARCHAR(60) NOT NULL, 
                                `wd1` VARCHAR(20) NOT NULL , `wd1Liquid` BOOLEAN NOT NULL,
                                `wd2` VARCHAR(20) NOT NULL , `wd2Liquid` BOOLEAN NOT NULL,
                                `wd3` VARCHAR(20) NOT NULL , `wd3Liquid` BOOLEAN NOT NULL,
                                `wd4` VARCHAR(20) NOT NULL , `wd4Liquid` BOOLEAN NOT NULL,
                                `wd5` VARCHAR(20) NOT NULL , `wd5Liquid` BOOLEAN NOT NULL,
                                `wd6` VARCHAR(20) NOT NULL , `wd6Liquid` BOOLEAN NOT NULL,
                                `wd7` VARCHAR(20) NOT NULL , `wd7Liquid` BOOLEAN NOT NULL,
                                `wd8` VARCHAR(20) NOT NULL , `wd8Liquid` BOOLEAN NOT NULL,
                                `wd9` VARCHAR(20) NOT NULL , `wd9Liquid` BOOLEAN NOT NULL,
                                `wd10` VARCHAR(20) NOT NULL , `wd10Liquid` BOOLEAN NOT NULL,
                                `revcat1` VARCHAR(20) NOT NULL,
                                `revcat2` VARCHAR(20) NOT NULL,
                                `revcat3` VARCHAR(20) NOT NULL,
                                `revcat4` VARCHAR(20) NOT NULL,
                                `revcat5` VARCHAR(20) NOT NULL,
                                `revcat6` VARCHAR(20) NOT NULL,
                                `revcat7` VARCHAR(20) NOT NULL,
                                `revcat8` VARCHAR(20) NOT NULL,
                                `revcat9` VARCHAR(20) NOT NULL,
                                `revcat10` VARCHAR(20) NOT NULL, 
                                `expcat1` VARCHAR(20) NOT NULL, 
                                `expcat2` VARCHAR(20) NOT NULL,
                                `expcat3` VARCHAR(20) NOT NULL,
                                `expcat4` VARCHAR(20) NOT NULL,
                                `expcat5` VARCHAR(20) NOT NULL,
                                `expcat6` VARCHAR(20) NOT NULL,
                                `expcat7` VARCHAR(20) NOT NULL,
                                `expcat8` VARCHAR(20) NOT NULL,
                                `expcat9` VARCHAR(20) NOT NULL,
                                `expcat10` VARCHAR(20) NOT NULL,
                                PRIMARY KEY (`id`)) ENGINE = InnoDB');
        $stmt->execute();                                                                                                                                                                                                                                                                         
        return;
    }

    public function addUser($username, $passwordHashed, $wealthdistarray, $wdliquidarray, $revcatarray, $expcatarray) {
        $stmt = $this->pdo->prepare("INSERT INTO `users` (`id`, `username`, `password`, `wd1`, `wd1Liquid`, `wd2`, `wd2Liquid`, `wd3`, `wd3Liquid`, `wd4`, `wd4Liquid`, `wd5`, `wd5Liquid`, `wd6`, `wd6Liquid`, `wd7`, `wd7Liquid`, `wd8`, `wd8Liquid`, `wd9`, `wd9Liquid`, `wd10`, `wd10Liquid`, `revcat1`, `revcat2`, `revcat3`, `revcat4`, `revcat5`, `revcat6`, `revcat7`, `revcat8`, `revcat9`, `revcat10`, `expcat1`, `expcat2`, `expcat3`, `expcat4`, `expcat5`, `expcat6`, `expcat7`, `expcat8`, `expcat9`, `expcat10`) 
                                        VALUES (NULL, :username, :passwordHashed, 
                                        :wd1, :wd1Liquid, :wd2, :wd2Liquid, :wd3, :wd3Liquid, :wd4, :wd4Liquid, :wd5, :wd5Liquid, :wd6, :wd6Liquid, :wd7, :wd7Liquid, :wd8, :wd8Liquid, :wd9, :wd9Liquid, :wd10, :wd10Liquid, 
                                        :revcat1, :revcat2, :revcat3, :revcat4, :revcat5, :revcat6, :revcat7, :revcat8, :revcat9, :revcat10,
                                        :expcat1, :expcat2, :expcat3, :expcat4, :expcat5, :expcat6, :expcat7, :expcat8, :expcat9, :expcat10)");
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':passwordHashed', $passwordHashed);
        $stmt->bindValue(':wd1', $wealthdistarray['wealthdist1'] ?? '');
        $stmt->bindValue(':wd1Liquid', $wdliquidarray['wd1liquid'] ?? '');
        $stmt->bindValue(':wd2', $wealthdistarray['wealthdist2'] ?? '');
        $stmt->bindValue(':wd2Liquid', $wdliquidarray['wd2liquid'] ?? '');
        $stmt->bindValue(':wd3', $wealthdistarray['wealthdist3'] ?? '');
        $stmt->bindValue(':wd3Liquid', $wdliquidarray['wd3liquid'] ?? '');
        $stmt->bindValue(':wd4', $wealthdistarray['wealthdist4'] ?? '');
        $stmt->bindValue(':wd4Liquid', $wdliquidarray['wd4liquid'] ?? '');
        $stmt->bindValue(':wd5', $wealthdistarray['wealthdist5'] ?? '');
        $stmt->bindValue(':wd5Liquid', $wdliquidarray['wd5liquid'] ?? '');
        $stmt->bindValue(':wd6', $wealthdistarray['wealthdist6'] ?? '');
        $stmt->bindValue(':wd6Liquid', $wdliquidarray['wd6liquid'] ?? '');
        $stmt->bindValue(':wd7', $wealthdistarray['wealthdist7'] ?? '');
        $stmt->bindValue(':wd7Liquid', $wdliquidarray['wd7liquid'] ?? '');
        $stmt->bindValue(':wd8', $wealthdistarray['wealthdist8'] ?? '');
        $stmt->bindValue(':wd8Liquid', $wdliquidarray['wd8liquid'] ?? '');
        $stmt->bindValue(':wd9', $wealthdistarray['wealthdist9'] ?? '');
        $stmt->bindValue(':wd9Liquid', $wdliquidarray['wd9liquid'] ?? '');
        $stmt->bindValue(':wd10', $wealthdistarray['wealthdist10'] ?? '');
        $stmt->bindValue(':wd10Liquid', $wdliquidarray['wd10liquid'] ?? '');

        $stmt->bindValue(':revcat1', $revcatarray['revcat1'] ?? '');
        $stmt->bindValue(':revcat2', $revcatarray['revcat2'] ?? '');
        $stmt->bindValue(':revcat3', $revcatarray['revcat3'] ?? '');
        $stmt->bindValue(':revcat4', $revcatarray['revcat4'] ?? '');
        $stmt->bindValue(':revcat5', $revcatarray['revcat5'] ?? '');
        $stmt->bindValue(':revcat6', $revcatarray['revcat6'] ?? '');
        $stmt->bindValue(':revcat7', $revcatarray['revcat7'] ?? '');
        $stmt->bindValue(':revcat8', $revcatarray['revcat8'] ?? '');
        $stmt->bindValue(':revcat9', $revcatarray['revcat9'] ?? '');
        $stmt->bindValue(':revcat10', $revcatarray['revcat10'] ?? '');

        $stmt->bindValue(':expcat1', $expcatarray['expcat1'] ?? '');
        $stmt->bindValue(':expcat2', $expcatarray['expcat2'] ?? '');
        $stmt->bindValue(':expcat3', $expcatarray['expcat3'] ?? '');
        $stmt->bindValue(':expcat4', $expcatarray['expcat4'] ?? '');
        $stmt->bindValue(':expcat5', $expcatarray['expcat5'] ?? '');
        $stmt->bindValue(':expcat6', $expcatarray['expcat6'] ?? '');
        $stmt->bindValue(':expcat7', $expcatarray['expcat7'] ?? '');
        $stmt->bindValue(':expcat8', $expcatarray['expcat8'] ?? '');
        $stmt->bindValue(':expcat9', $expcatarray['expcat9'] ?? '');
        $stmt->bindValue(':expcat10', $expcatarray['expcat10'] ?? '');
        
        $stmt->execute();
        return;
    }

    public function updateUsername($oldUsername, $newUsername) {
        $stmt = $this->pdo->prepare('UPDATE `users` SET `username` = :newusername WHERE `username` = :oldusername');
        $stmt->bindValue(':newusername', $newUsername);
        $stmt->bindValue(':oldusername', $oldUsername);
        $stmt->execute();
        return;
    }

    public function updatePassword($username, $changedPassword) {
        $stmt = $this->pdo->prepare('UPDATE `users` SET `password` = :password WHERE `username` = :username');
        $stmt->bindValue(':password', $changedPassword);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        return;
    }

    public function updateCategories($catsKeys, $catsValues) {
        $username = $_SESSION['username'];
        $editArray = [];
        for ($i = 0; $i < count($catsKeys); $i++) {
            $editArray[] = "`{$catsKeys[$i]}` = '{$catsValues[$i]}' ";
        }
        $editString = implode(",", $editArray);
        $query = 'UPDATE `users` SET ' . "{$editString}" . 'WHERE `username` = :username';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        return;
    }

    public function deleteCategory($category) {
        $username = $_SESSION['username'];
        foreach ($category as $key => $value) {
            $deleteString = "`{$key}` = ''";
        }
        $query = 'UPDATE `users` SET ' . "{$deleteString}" . ' WHERE `username` = :username';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        return;
       
    }
}