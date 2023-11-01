<?php 

namespace App\Yearly;

use PDO;

class YearlyRepository {

    protected string $username;

    public function __construct(
        protected PDO $pdo) {
            $this->username = isset($_SESSION['username']) ? strtolower($_SESSION['username']) : '';
        }

    public function generateTable($username) {
        $query = 'CREATE TABLE `geniusbudgetbook`.' . "`{$username}" . 'yearly` (`id` INT(11) NOT NULL AUTO_INCREMENT , `year` INT(4) NOT NULL , `donationgoal` INT(11) NOT NULL , `savinggoal` INT(11) NOT NULL , `totalwealthgoal` INT(11) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();       
        return;
    }

    public function add($username, $year, $donationgoal, $savinggoal, $totalwealthgoal) {
        $query = 'INSERT INTO ' . "`{$username}" . 'yearly` (`id`, `year`, `donationgoal`, `savinggoal`, `totalwealthgoal`) VALUES (NULL, :year, :donationgoal, :savinggoal, :totalwealthgoal)';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':year', $year);
        $stmt->bindValue(':donationgoal', $donationgoal);
        $stmt->bindValue(':savinggoal', $savinggoal);
        $stmt->bindValue(':totalwealthgoal', $totalwealthgoal);
        $stmt->execute();       
        return;
    }

    public function update($year, $donationgoal, $savinggoal, $totalwealthgoal) {
        $query = 'UPDATE ' . "`{$this->username}" . 'yearly` SET `donationgoal` = :donationgoal, `savinggoal` = :savinggoal, `totalwealthgoal` = :totalwealthgoal WHERE `year` = :year';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':donationgoal', $donationgoal);
        $stmt->bindValue(':savinggoal', $savinggoal);
        $stmt->bindValue(':totalwealthgoal', $totalwealthgoal);
        $stmt->bindValue(':year', $year);
        $stmt->execute();
        return;
    }

    public function fetchAllOfYear($year) {
        $query = 'SELECT * FROM' . "`{$this->username}" . 'yearly` WHERE `year` = :year';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':year', $year);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function updateTablename($oldUsername, $newUsername) {
        $query = 'ALTER TABLE ' . "`{$oldUsername}" . 'yearly` RENAME TO' . "`{$newUsername}" . 'yearly`';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return;
    }
}