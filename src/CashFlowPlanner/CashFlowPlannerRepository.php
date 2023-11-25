<?php 

namespace App\CashFlowPlanner;

use PDO;

class CashFlowPlannerRepository {

    protected string $username;

    public function __construct(
        protected PDO $pdo) {
            $this->username = isset($_SESSION['username']) ? strtolower($_SESSION['username']) : '';
        }

    public function tableExisting(): bool {
        $query = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'geniusbudgetbook' AND TABLE_NAME = '" . $this->username . "planner'";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function generateTable() {
        $query = 'CREATE TABLE `geniusbudgetbook`.' . "`{$this->username}" . "planner` 
                (
                    `revName1` VARCHAR(50) NOT NULL, `revAmount1`FLOAT NOT NULL,
                    `revName2` VARCHAR(50) NOT NULL, `revAmount2`FLOAT NOT NULL,
                    `revName3` VARCHAR(50) NOT NULL, `revAmount3`FLOAT NOT NULL,
                    `revName4` VARCHAR(50) NOT NULL, `revAmount4`FLOAT NOT NULL,
                    `revName5` VARCHAR(50) NOT NULL, `revAmount5`FLOAT NOT NULL,
                    `revName6` VARCHAR(50) NOT NULL, `revAmount6`FLOAT NOT NULL,
                    `revName7` VARCHAR(50) NOT NULL, `revAmount7`FLOAT NOT NULL,
                    `revName8` VARCHAR(50) NOT NULL, `revAmount8`FLOAT NOT NULL,
                    `revName9` VARCHAR(50) NOT NULL, `revAmount9`FLOAT NOT NULL,
                    `revName10` VARCHAR(50) NOT NULL, `revAmount10`FLOAT NOT NULL,
                    `expName1` VARCHAR(50) NOT NULL, `expAmount1`FLOAT NOT NULL,
                    `expName2` VARCHAR(50) NOT NULL, `expAmount2`FLOAT NOT NULL,
                    `expName3` VARCHAR(50) NOT NULL, `expAmount3`FLOAT NOT NULL,
                    `expName4` VARCHAR(50) NOT NULL, `expAmount4`FLOAT NOT NULL,
                    `expName5` VARCHAR(50) NOT NULL, `expAmount5`FLOAT NOT NULL,
                    `expName6` VARCHAR(50) NOT NULL, `expAmount6`FLOAT NOT NULL,
                    `expName7` VARCHAR(50) NOT NULL, `expAmount7`FLOAT NOT NULL,
                    `expName8` VARCHAR(50) NOT NULL, `expAmount8`FLOAT NOT NULL,
                    `expName9` VARCHAR(50) NOT NULL, `expAmount9`FLOAT NOT NULL,
                    `expName10` VARCHAR(50) NOT NULL, `expAmount10`FLOAT NOT NULL
                ) ENGINE = InnoDB";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();                                                                                                                                                                                                                                                                         
        return;
    }

    public function create($queryString): bool {
        $query = 'INSERT INTO  ' . "`{$this->username}" . 'planner`' . $queryString;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return true;
    }

    public function update($queryString) {
        $query = 'UPDATE  ' . "`{$this->username}" . 'planner` SET ' . $queryString;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }

    public function fetch(): array {
        $query = 'SELECT * FROM ' . "`{$this->username}" . 'planner`';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        // return $stmt->fetchAll(PDO::FETCH_CLASS, CashFlowPlannerModel::class);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}