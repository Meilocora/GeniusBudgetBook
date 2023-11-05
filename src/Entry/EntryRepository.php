<?php 

namespace App\Entry;

use DateTime;
use PDO;

class EntryRepository {

    protected string $username;

    public function __construct(
        protected PDO $pdo) {
            $this->username = isset($_SESSION['username']) ? strtolower($_SESSION['username']) : '';
        }

    public function generateTable($username) {
        $query = 'CREATE TABLE `geniusbudgetbook`.' . "`{$username}" . 'entries` (`id` INT(11) NOT NULL AUTO_INCREMENT , `category` VARCHAR(50) NOT NULL , `title` VARCHAR(50) NOT NULL , `amount` FLOAT NOT NULL , `dateslug` DATE NOT NULL , `comment` VARCHAR(1024) NOT NULL , `income` BOOLEAN NOT NULL , `fixedentry` BOOLEAN NOT NULL , PRIMARY KEY (`id`), INDEX (`category`), INDEX (`title`)) ENGINE = InnoDB';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();                                                                                                                                                                                                                                                                         
        return;
    }

    #TODO: Selbe Funktion wie die darunter ?!
    public function fetchAllOfMonth($date): array {
        $dateClass = new DateTime($date);
        $monthFstDay = $dateClass->modify('first day of this month')->format('Y-m-d');
        $monthLstDay = $dateClass->modify('last day of this month')->format('Y-m-d');
        $query = 'SELECT * FROM ' . "`{$this->username}" . 'entries` WHERE `dateslug` BETWEEN :monthFstDay AND :monthLstDay ORDER BY `dateslug` ASC';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':monthFstDay', $monthFstDay);
        $stmt->bindValue(':monthLstDay', $monthLstDay);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, EntryModel::class);
    }

    public function fetchAllOfGivenMonth($date): array {
        $dateClass = new DateTime($date);
        $monthFstDay = $dateClass->modify('first day of this month')->format('Y-m-d');
        $monthLstDay = $dateClass->modify('last day of this month')->format('Y-m-d');
        $query = 'SELECT * FROM ' . "`{$this->username}" . 'entries` WHERE `dateslug` BETWEEN :monthFstDay AND :monthLstDay ORDER BY `dateslug` ASC';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':monthFstDay', $monthFstDay);
        $stmt->bindValue(':monthLstDay', $monthLstDay);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, EntryModel::class);
    }

    public function fetchAllForTimeInterval($startDate, $endDate) {
        $query = 'SELECT * FROM' . "`{$this->username}" . 'entries` WHERE `dateslug` BETWEEN :startdate AND :enddate';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':startdate', $startDate);
        $stmt->bindValue(':enddate', $endDate);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, EntryModel::class);
    } 

    public function fetchAllOfMonthPerPageSortedByProperty($date,$sortingProperty, $sortMode, $perPage, $currentPage): array {
        $dateClass = new DateTime($date);
        $monthFstDay = $dateClass->modify('first day of this month')->format('Y-m-d');
        $monthLstDay = $dateClass->modify('last day of this month')->format('Y-m-d');
        $limit = $perPage;
        $offset = ($currentPage - 1) * $perPage;
        $query = 'SELECT * FROM ' . "`{$this->username}" . "entries` WHERE `dateslug` BETWEEN :monthFstDay AND :monthLstDay ORDER BY `{$sortingProperty}` {$sortMode} LIMIT {$limit} OFFSET {$offset}";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':monthFstDay', $monthFstDay);
        $stmt->bindValue(':monthLstDay', $monthLstDay);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, EntryModel::class);
    }

    public function countEntriesOfMonth($date) {
        $dateClass = new DateTime($date);
        $monthFstDay = $dateClass->modify('first day of this month')->format('Y-m-d');
        $monthLstDay = $dateClass->modify('last day of this month')->format('Y-m-d');
        $query = 'SELECT COUNT(*) AS `count` FROM ' . "`{$this->username}" . 'entries` WHERE `dateslug` BETWEEN :monthFstDay AND :monthLstDay ';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':monthFstDay', $monthFstDay);
        $stmt->bindValue(':monthLstDay', $monthLstDay);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function create(string $category, string $title, float $amount, string $date, string $comment, int $income, int $fixedentry): bool {
        $query = 'INSERT INTO  ' . "`{$this->username}" . 'entries` (category, title, amount, dateslug, comment, income, fixedentry) VALUES (:category, :title, :amount, :dateslug, :comment, :income, :fixedentry) ';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':dateslug', $date);
        $stmt->bindValue(':comment', $comment);
        $stmt->bindValue(':income', $income);
        $stmt->bindValue(':fixedentry', $fixedentry);
        $stmt->execute();
        return true;
    }

    public function delete($id) {
        $query = 'DELETE FROM  ' . "`{$this->username}" . 'entries` WHERE `id`=:id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function update(int $id, string $category, string $title, float $amount, string $dateslug, string $comment, int $income, int $fixedentry) {
        $query = 'UPDATE  ' . "`{$this->username}" . 'entries` SET `category` = :category, `title` = :title, `amount` = :amount, `dateslug` = :dateslug, `comment` = :comment, `income` = :income, `fixedentry` = :fixedentry WHERE `id` = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':dateslug', $dateslug);
        $stmt->bindValue(':comment', $comment);
        $stmt->bindValue(':income', $income);
        $stmt->bindValue(':fixedentry', $fixedentry);
        $stmt->execute();
    }

    public function updateTablename($oldUsername, $newUsername) {
        $query = 'ALTER TABLE ' . "`{$oldUsername}" . 'entries` RENAME TO' . "`{$newUsername}" . 'entries`';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return;
    }

    public function updateEntryCategory($changeEntries) {
        foreach ($changeEntries as $key => $value) {
            $query = 'UPDATE  ' . "`{$this->username}" . "entries` SET `category` = '{$value}' WHERE `category` = '{$key}'";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
        return;
    }

    // should be in EntryController, but there is a memory_limit error, when adding this controller to UsersController...
    public function deleteEntryCategory($deleteCategory) {
        $deleteEntries = $this->fetchByCategory($deleteCategory);
        foreach ($deleteEntries as $entry) {
            $this->delete($entry->id);
        }
        return;
    }

    public function fetchByCategory($category) {
        $query = 'SELECT * FROM ' . "`{$this->username}" . 'entries` WHERE `category` = :category';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue('category', array_values($category)[0]);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, EntryModel::class);
    }

    public function fetchfirstEntry() {
        $query ='SELECT * FROM ' . "`{$this->username}" . 'entries` ORDER BY `dateslug` ASC';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_CLASS, EntryModel::class);
        return ($results[0]);
    }

}