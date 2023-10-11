<?php 

namespace App\Controller;

use App\DBInitialize\DBInitialize;
use App\DBInitialize\DBQuery;
use App\Controller\LoginController;
use App\Controller\YearlyController;
use App\Controller\UsersController;

class DBController extends AbstractController {
    public function __construct(
        protected DBInitialize $dbInitialize,
        protected DBQuery $dbQuery,
        protected LoginController $loginController,
        protected YearlyController $yearlyController,
        protected UsersController $usersController) {}

    public function sandboxInitialize() {
        $username ='sandboxuser';
        $password = 'sandboxuser';
        $wealthdistarray = [
            "wealthdist1"=>"Bank Account",
            "wealthdist2"=>"Savings Account",
            "wealthdist3"=>"Stocks",
            "wealthdist4"=>"Real Estate",
            "wealthdist5"=>"State sponsored fund",
            "wealthdist6"=>"Collectibles"
        ];
        $wdliquidarray = [
            "wd1liquid"=>"1",
            "wd2liquid"=>"1",
            "wd3liquid"=>"",
            "wd4liquid"=>"",
            "wd5liquid"=>"",
            "wd6liquid"=>""
        ];
        $revcatarray = [
            "revcat1"=>"Salary",
            "revcat2"=>"Passive Income",
            "revcat3"=>"Bonus"
        ];
        $expcatarray = [
            "expcat1"=>"Rent",
            "expcat2"=>"Food",
            "expcat3"=>"Insurances",
            "expcat4"=>"Leisure",
            "expcat5"=>"Journeys",
            "expcat6"=>"Education",
            "expcat7"=>"Transportation",
            "expcat8"=>"Other Contracts",
            "expcat9"=>"Donations",
            "expcat10"=>"Gifts"
        ];
        $donationgoal = @(float) (400);
        $savinggoal = @(float) (1000);
        $totalwealthgoal = @(float) (53000);

        $this->dbInitialize->sandboxInitialize($username, $password, $wealthdistarray, $wdliquidarray, $revcatarray, $expcatarray, $donationgoal, $savinggoal, $totalwealthgoal);
        $_POST['username'] = $username;
        $_POST['password'] = $password;
        $this->loginController->login();
    }

    public function usersInitialize() {
        $this->dbInitialize->usersInitialize();
    }

    public function addUser() {
        $username = @(string) ($_POST['newUsername'] ?? 'Max Mustermann');
        $password = @(string) ($_POST['newUserPw'] ?? 'Musterpasswort');
        $newUserArray = $_POST;
        $wealthdistarray = [];
        $wdliquidarray = [];
        $revcatarray = [];
        $expcatarray = [];
        foreach($newUserArray AS $key => $value) {
            if(preg_match('/^wealthdist{1,2}\d$/', $key) && $value != '') {
                $wealthdistarray[$key] = trim($value);
            }
            elseif(preg_match('/^wd{1,2}\dliquid$/', $key) && $value != '') {
                $wdliquidarray[$key] = trim($value);
            }
            elseif(preg_match('/^revcat{1,2}\d$/', $key) && $value != '') {
                $revcatarray[$key] = trim($value);
            }
            elseif(preg_match('/^expcat{1,2}\d$/', $key) && $value != '') {
                $expcatarray[$key] = trim($value);
            }
        }

        $donationgoal = @(float) ($_POST['donationgoal'] ?? '');
        $savinggoal = @(float) ($_POST['savinggoal'] ?? '');
        $totalwealthgoal = @(float) ($_POST['totalwealthgoal'] ?? '');

        $newUserOk = $this->usersController->addUser($username, $password, $wealthdistarray, $wdliquidarray, $revcatarray, $expcatarray, $donationgoal, $savinggoal, $totalwealthgoal);
        if($newUserOk) {
            return true;
        }
        else{
            return $errorMessage = '- username already exists'; 
        }
    }
}