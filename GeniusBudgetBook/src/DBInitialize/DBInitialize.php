<?php 

namespace App\DBInitialize;

use PDO;
use App\DBInitialize\DBQuery;
use App\Users\UsersRepository;
use App\WealthDistribution\WDRepository;
use App\Entry\EntryRepository;
use App\Controller\YearlyController;

class DBInitialize {
    public function __construct(
        protected PDO $pdo,
        protected DBQuery $dbQuery,
        protected UsersRepository $usersRepository,
        protected WDRepository $wDRepository,
        protected EntryRepository $entryRepository,
        protected YearlyController $yearlyController) {}

    // If nessecary generates the Database "geniusbudgetbook" and the table "sandboxuser" with exemplary entries
    public function sandboxInitialize($username, $password, $wealthdistarray, $wdliquidarray, $revcatarray, $expcatarray, $donationgoal, $savinggoal, $totalwealthgoal) {
        $this->usersInitialize();
        $userExisting = $this->usersRepository->userExisting($username);
        if($userExisting) {
            $this->sandboxDropTables();
            $this->wDRepository->generateTable($username);
            $this->entryRepository->generateTable($username);
            $this->sandboxEntriesFill();
            $this->yearlyController->generateAndFillTableYearly($username, $donationgoal, $savinggoal, $totalwealthgoal);
            return;
        } 
        else {
            $this->usersRepository->addUser($username, $password, $wealthdistarray, $wdliquidarray, $revcatarray, $expcatarray);
            $this->wDRepository->generateTable($username);
            $this->entryRepository->generateTable($username);
            $this->sandboxEntriesFill();
            $this->yearlyController->generateAndFillTableYearly($username, $donationgoal, $savinggoal, $totalwealthgoal);
            return;
        }
    }

    public function databaseGenerate() {
        $stmt = $this->pdo->prepare("CREATE DATABASE `geniusbudgetbook`");
        $stmt->execute();
        $this->databaseSelect();
        return;
    }

    public function databaseSelect() {
        $stmt = $this->pdo->prepare("USE `geniusbudgetbook`");
        $stmt->execute();
        return;
    }

    public function sandboxDropTables() {
        $stmt = $this->pdo->prepare("DROP TABLE IF EXISTS `sandboxuserwdmonthly`");
        $stmt->execute();
        $stmt = $this->pdo->prepare("DROP TABLE IF EXISTS `sandboxuserentries`");
        $stmt->execute();
        $stmt = $this->pdo->prepare("DROP TABLE IF EXISTS `sandboxuseryearly`");
        $stmt->execute();
        return;
    }

    public function sandboxEntriesFill() {
        function createRandCurrentDateArray ($month) {
            $randCurrentDateArray = [];
            for($x = 0; $x < 8; $x++) {
                $day = @(string) rand(1, 29);
                $randCurrentDateArray[] = "'" . date('Y-' . $month . '-' . $day). "'";
            }
            return $randCurrentDateArray;
        }
        $randDateArrayThisMonth = createRandCurrentDateArray(date('m'));
        $randDateArrayLastMonth = createRandCurrentDateArray(date('m',strtotime('-1 month')));
        $query = "INSERT INTO `sandboxuserentries` (`id`, `category`, `title`, `amount`, `dateslug`, `comment`, `income`, `fixedentry`) VALUES 
        (NULL, 'Passive Income', 'Interest', 125, {$randDateArrayLastMonth[0]}, '', 1, 1), 
        (NULL, 'Bonus', 'Extra payment', 500, {$randDateArrayLastMonth[1]}, '', 1, 0),
        (NULL, 'Rent', 'Rent flat', 750, {$randDateArrayLastMonth[2]}, '', 0, 1),
        (NULL, 'Salary', 'Job Salary', 2300, {$randDateArrayThisMonth[0]}, '', 1, 0), 
        (NULL, 'Rent', 'Rent flat', 750, {$randDateArrayThisMonth[1]}, '', 0, 1),
        (NULL, 'Insurances', 'Car', 67, {$randDateArrayThisMonth[2]}, '', 0, 1),
        (NULL, 'Insurances', 'Household Insurance', 12, {$randDateArrayThisMonth[3]}, '', 0, 1), 
        (NULL, 'Gifts', 'Mother', 35, {$randDateArrayThisMonth[4]}, '', 0, 0), 
        (NULL, 'Journeys', 'Italie', 750, {$randDateArrayThisMonth[5]}, '', 0, 0),
        (NULL, 'Leisure', 'Party', 80, {$randDateArrayThisMonth[6]}, 'Went to the Club tonight', 0, 0),
        (NULL, 'Transportation', 'Car refill', 90, {$randDateArrayThisMonth[7]}, '1,95€ per litre', 0, 0)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $fstOfMonth = "'" . date('Y-m'). "-01'";
        $query = "INSERT INTO `sandboxuserwdmonthly` (`id`, `dateslug`, `Bank Account-1-target`, `Bank Account-1-actual`,  `Savings Account-1-target`, `Savings Account-1-actual`, `Stocks-0-target`, `Stocks-0-actual`, `Real Estate-0-target`, `Real Estate-0-actual`,`State sponsored fund-0-target`, `State sponsored fund-0-actual`, `Collectibles-0-target`, `Collectibles-0-actual`) VALUES (NULL, {$fstOfMonth}, '2700', '3000', '5600','5400', '3200', '3750', '12000', '15400', '5000', '3250', '740', '730')";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }

    // If nessecary generates the Database "geniusbudgetbook" and the table "users"
    public function usersInitialize() {
        $databaseExisting = $this->dbQuery->databaseExisting();
        if(!$databaseExisting) {
            $this->databaseGenerate();
        } else {
            $this->databaseSelect();
        }
        $usersExisting = $this->dbQuery->usersExisting();
        if(!$usersExisting) {
            $this->usersRepository->generateTable();
        }
        return;
    }
}