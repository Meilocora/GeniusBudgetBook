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
            for($x = 0; $x < 9; $x++) {
                $day = @(string) rand(1, date('d'));
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
        (NULL, 'Transportation', 'Car refill', 90, {$randDateArrayThisMonth[7]}, '1,95€ per litre', 0, 0),
        (NULL, 'Gifts', 'Donation', 50, {$randDateArrayThisMonth[7]}, 'Animal Rescue Center', 0, 0),
        (NULL, 'Gifts', 'Gift', 20, {$randDateArrayThisMonth[7]}, 'second donation', 0, 0)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $dates= [];
        for($x=0; $x<14; $x++) {
            $dates[] = "'" . date('Y-m',strtotime("-${x} month")) . "-01'";
        }
       
        $query = "INSERT INTO `sandboxuserwdmonthly` (`id`, `dateslug`, `Bank Account-1-target`, `Bank Account-1-actual`,  `Savings Account-1-target`, `Savings Account-1-actual`, `Stocks-0-target`, `Stocks-0-actual`, `Real Estate-0-target`, `Real Estate-0-actual`,`State sponsored fund-0-target`, `State sponsored fund-0-actual`, `Collectibles-0-target`, `Collectibles-0-actual`) VALUES
            (NULL, {$dates[13]}, '2700', '2700', '6000','6005', '3400', '3050', '12000', '13100', '2950', '2370', '1200', '1385'), 
            (NULL, {$dates[12]}, '2450', '2450', '5800','5805', '3300', '2750', '12000', '12950', '2800', '2320', '1200', '1372'), 
            (NULL, {$dates[11]}, '2400', '2400', '5600','5605', '3200', '2550', '12000', '12900', '2650', '2120', '1200', '1365'),
            (NULL, {$dates[10]}, '2500', '2500', '5400','5405', '3100', '2750', '12000', '12800', '2500', '2010', '1200', '1380'),
            (NULL, {$dates[9]}, '2400', '2400', '5200','5205', '3000', '2800', '12000', '12700', '2350', '1950', '1200', '1370'),
            (NULL, {$dates[8]}, '2300', '2300', '5000','5000', '2900', '2850', '12000', '12650', '2200', '1970', '1200', '1355'),
            (NULL, {$dates[7]}, '2000', '2000', '4800','4800', '2800', '3000', '12000', '12600', '2050', '2000', '1200', '1360'),
            (NULL, {$dates[6]}, '2200', '2200', '4600','4600', '2700', '3300', '12000', '12550', '1900', '2120', '1200', '1350'),
            (NULL, {$dates[5]}, '2000', '2000', '4400','4400', '2600', '3150', '12000', '12500', '1750', '1920', '740', '770'),
            (NULL, {$dates[4]}, '1850', '1850', '4200','4200', '2500', '3000', '12000', '12450', '1600', '1810', '740', '770'),
            (NULL, {$dates[3]}, '2050', '2050', '4000','4000', '2400', '2760', '12000', '12400', '1450', '1540', '740', '735'),
            (NULL, {$dates[2]}, '2200', '2200', '3800','3800', '2300', '2600', '12000', '12350', '1300', '1360', '740', '735'),
            (NULL, {$dates[1]}, '2500', '2500', '3600','3600', '2200', '2600', '12000', '12300', '1150', '1170', '740', '735'),
            (NULL, {$dates[0]}, '2350', '2350', '3400','3400', '2100', '2350', '12000', '12100', '1000', '1050', '740', '730')";
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