<?php 

namespace App\Yearly;

use PDO;

class YearlyRepository {
    public function __construct(
        protected PDO $pdo) {}

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


}