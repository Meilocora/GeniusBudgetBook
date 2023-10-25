<?php 

namespace App\Controller;

use App\Users\UsersRepository;
use App\WealthDistribution\WDRepository;
use App\Entry\EntryRepository;
use App\Controller\YearlyController;


class UsersController extends AbstractController {
    public function __construct(
        protected UsersRepository $usersRepository,
        protected WDRepository $wdRepository,
        protected EntryRepository $entryRepository,
        protected YearlyController $yearlyController) {}

    public function addUser($username, $password, $wealthdistarray, $wdliquidarray, $revcatarray, $expcatarray, $donationgoal, $savinggoal, $totalwealthgoal): bool{    
        $userExisting = $this->usersRepository->userExisting($username);
        if(!empty($userExisting)) {
            return false;
        } 
        else {
            $passwordHashed = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);    
            $this->usersRepository->addUser($username, $passwordHashed, $wealthdistarray, $wdliquidarray, $revcatarray, $expcatarray);
            $this->wdRepository->generateTable($username);
            $this->entryRepository->generateTable($username);
            $this->yearlyController->generateAndFillTableYearly($username, $donationgoal, $savinggoal, $totalwealthgoal);
            return true;
        }
    }

    public function usersEntryCats($username) {
        $userData = $this->usersRepository->fetchAll($username);
        $categories= [];
        foreach($userData AS $data) {
            foreach($data AS $key => $value) {
                if(preg_match('/^expcat{1,2}\d$/', $key) && $value != '') {
                    $categories[] = $value;
                }
                if(preg_match('/^revcat{1,2}\d$/', $key) && $value != '') {
                    $categories[] = $value;
                }
            }

        }
        return $categories;
    }

    public function usersExpCats($username) {
        $userData = $this->usersRepository->fetchAll($username);
        $categories= [];
        foreach($userData AS $data) {
            foreach($data AS $key => $value) {
                if(preg_match('/^expcat{1,2}\d$/', $key) && $value != '') {
                    $categories[] = $value;
            }
        }
        return $categories;
        }
    }
}