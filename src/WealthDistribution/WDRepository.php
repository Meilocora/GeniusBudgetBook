<?php 

namespace App\WealthDistribution;

use App\Users\UsersRepository;
use PDO;

class WDRepository {
    public function __construct(
        protected PDO $pdo,
        protected UsersRepository $usersRepository) {}

    public function generateTable($username) {
        $wdCategories = $this->usersRepository->fetchWDCategories($username);
        $wdCategoriesValues = array_values($wdCategories);   
        $queryArray = [];

        for($x = 0; $x <= sizeof($wdCategoriesValues)-1; $x=$x+2) {
            $queryArray[] = "`{$wdCategoriesValues[$x]}-{$wdCategoriesValues[$x+1]}-target` VARCHAR(50) NOT NULL, `{$wdCategoriesValues[$x]}-{$wdCategoriesValues[$x+1]}-actual` VARCHAR(50) NOT NULL";
        }  
        $queryAddColumns = implode(', ', $queryArray);
        $query = 'CREATE TABLE `geniusbudgetbook`.' . "`{$username}" . 'wdmonthly` (`id` INT(11) NOT NULL AUTO_INCREMENT , `dateslug` DATE NOT NULL , ' . $queryAddColumns . ' , PRIMARY KEY (`id`)) ENGINE = InnoDB';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return;    
    }

    public function fetchAllOfMonth($username, $date): array {
        $dateSQL = date('Y-m-d', strtotime($date . '-01'));
        $query = 'SELECT * FROM' . "`{$username}" . 'wdmonthly` WHERE `dateslug` = :date';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':date', $dateSQL);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchAll($username) {
        $query = 'SELECT * FROM' . "`{$username}" . 'wdmonthly`';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function update($username, $date, $wdUpdateArray) {    
        $date = $date . '-01';
        $queryArray = [];
        foreach($wdUpdateArray AS $key => $value) {
            $queryArray[] = "`{$key}` = {$value}";
        }  
        $queryString = implode(', ', $queryArray);
        $query = 'UPDATE ' . "`{$username}" . 'wdmonthly` SET ' . $queryString . ' WHERE `dateslug` = :date';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':date', $date);
        $stmt->execute();
        return;
    }

    public function create($username, $date, $wdCreateArray) {
        $dateSQL = date('Y-m-d', strtotime($date . '-01'));
        $queryArrayColumns = [];
        $queryArrayValues = [];
        foreach($wdCreateArray AS $key => $value) {
            $queryArrayColumns[] = "`{$key}`";
            $queryArrayValues[] = "{$value}";
        }  
        $queryStringColumns = implode(', ', $queryArrayColumns);
        $queryStringValues = implode(', ', $queryArrayValues);
        $query = 'INSERT INTO  ' . "`{$username}" . 'wdmonthly` ( `id`, `dateslug`, ' . $queryStringColumns . ') VALUES (NULL, :date, ' . $queryStringValues . ')';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':date', $dateSQL);
        $stmt->execute();
        return;
    }
}