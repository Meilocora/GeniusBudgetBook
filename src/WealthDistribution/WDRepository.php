<?php 

namespace App\WealthDistribution;

use App\Users\UsersRepository;
use PDO;

class WDRepository {

    public string $username;

    public function __construct(
        protected PDO $pdo,
        protected UsersRepository $usersRepository) {
            $this->username = isset($_SESSION['username']) ? strtolower($_SESSION['username']) : '';
        }

    public function generateTable($username) {
        $wdCategories = $this->usersRepository->fetchWDCategories($username);
        $wdCategoriesValues = array_values($wdCategories);   
        $queryArray = [];

        for($x = 0; $x <= sizeof($wdCategoriesValues)-1; $x=$x+2) {
            $queryArray[] = "`{$wdCategoriesValues[$x]}-{$wdCategoriesValues[$x+1]}-target` VARCHAR(20) NOT NULL, `{$wdCategoriesValues[$x]}-{$wdCategoriesValues[$x+1]}-actual` VARCHAR(20) NOT NULL";
        }  
        $queryAddColumns = implode(', ', $queryArray);
        $query = 'CREATE TABLE `geniusbudgetbook`.' . "`{$username}" . 'wdmonthly` (`id` INT(11) NOT NULL AUTO_INCREMENT , `dateslug` DATE NOT NULL , ' . $queryAddColumns . ' , PRIMARY KEY (`id`)) ENGINE = InnoDB';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return;    
    }

    public function fetchAllOfMonth($date): array {
        $dateSQL = date('Y-m-d', strtotime($date . '-01'));
        $query = 'SELECT * FROM' . "`{$this->username}" . 'wdmonthly` WHERE `dateslug` = :date';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':date', $dateSQL);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchAll() {
        $query = 'SELECT * FROM' . "`{$this->username}" . 'wdmonthly`';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchAllForTimeInterval($startDate, $endDate) {
        $query = 'SELECT * FROM' . "`{$this->username}" . 'wdmonthly` WHERE `dateslug` BETWEEN :startdate AND :enddate';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':startdate', $startDate);
        $stmt->bindValue(':enddate', $endDate);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function update($date, $wdUpdateArray) {    
        $date = $date . '-01';
        $queryArray = [];
        foreach($wdUpdateArray AS $key => $value) {
            $queryArray[] = "`{$key}` = {$value}";
        }  
        $queryString = implode(', ', $queryArray);
        $query = 'UPDATE ' . "`{$this->username}" . 'wdmonthly` SET ' . $queryString . ' WHERE `dateslug` = :date';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':date', $date);
        $stmt->execute();
        return;
    }

    public function create($date, $wdCreateArray) {
        $dateSQL = date('Y-m-d', strtotime($date . '-01'));
        $queryArrayColumns = [];
        $queryArrayValues = [];
        foreach($wdCreateArray AS $key => $value) {
            $queryArrayColumns[] = "`{$key}`";
            $queryArrayValues[] = "{$value}";
        }  
        $queryStringColumns = implode(', ', $queryArrayColumns);
        $queryStringValues = implode(', ', $queryArrayValues);
        $query = 'INSERT INTO  ' . "`{$this->username}" . 'wdmonthly` ( `id`, `dateslug`, ' . $queryStringColumns . ') VALUES (NULL, :date, ' . $queryStringValues . ')';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':date', $dateSQL);
        $stmt->execute();
        return;
    }

    public function updateTablename($oldUsername, $newUsername) {
        $query = 'ALTER TABLE ' . "`{$oldUsername}" . 'wdmonthly` RENAME TO' . "`{$newUsername}" . 'wdmonthly`';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return;
    }

    public function renameColumn($columnOld, $columnNew) {
        // $query = 'ALTER TABLE ' . "`{$username}" . 'wdmonthly` RENAME COLUMN ' . "`{$columnOld}`" . ' TO ' .  "`{$columnNew}`"; // won't work for some reason
        $query = 'ALTER TABLE ' . "`{$this->username}" . 'wdmonthly` CHANGE ' . "`{$columnOld}` `{$columnNew}` VARCHAR(20)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return;
    }

    public function createColumns($category) {
        $username = $_SESSION['username'];
        $query = 'ALTER TABLE ' . "`{$username}" . 'wdmonthly` ADD '. "`{$category}-target` VARCHAR(20) NOT NULL AFTER `dateslug`, ADD `{$category}-actual` VARCHAR(20) NOT NULL AFTER `{$category}-target`";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return;
    }

    public function dropColumns($columnTarget, $columnActual) {
        $username = $_SESSION['username'];
        $query = 'ALTER TABLE ' . "`{$username}" . 'wdmonthly` DROP COLUMN ' . "`{$columnTarget}`," . ' DROP COLUMN ' . "`{$columnActual}`";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return;
    }

    public function fetchfirstBalance() {
        $query ='SELECT * FROM ' . "`{$this->username}" . 'wdmonthly` ORDER BY `dateslug` ASC';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(isset($results[0])) return $results[0]; else return null;
    }
}