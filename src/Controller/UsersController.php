<?php 

namespace App\Controller;

use App\Users\UsersRepository;
use App\WealthDistribution\WDRepository;
use App\Entry\EntryRepository;
use App\Controller\YearlyController;
use App\Yearly\YearlyRepository;


class UsersController extends AbstractController {
    public function __construct(
        protected UsersRepository $usersRepository,
        protected WDRepository $wdRepository,
        protected EntryRepository $entryRepository,
        protected YearlyController $yearlyController,
        protected YearlyRepository $yearlyRepository) {}

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
                if(preg_match('/^expcat\d{1,2}$/', $key) && $value != '') {
                    $categories[] = $value;
                }
                if(preg_match('/^revcat\d{1,2}$/', $key) && $value != '') {
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

    public function fetchUsernames() {
        $userData = $this->usersRepository->fetchAllData();
        $usernames = [];
        foreach($userData AS $arry) {
            foreach($arry AS $key => $value) {
                if($key === 'username') $usernames[] = $value;
            }
        }
        return $usernames;
    }

    public function changeUserData() {
        if(isset($_POST['changedUsername'])) {
            $oldUsername = $_SESSION['username'];
            $changedUsername = trim(@(string) $_POST['changedUsername']);
            $errorArray = $this->validateUsername($changedUsername);
            if(!empty($errorArray)) {
                $_SESSION['errorArray'] = implode(',', $errorArray);
                return header('Location: ./?route=userSettings');
            }
            $_SESSION['username'] = $changedUsername;
            $this->usersRepository->updateUsername($oldUsername, $changedUsername);
            $this->entryRepository->updateTablename($oldUsername, $changedUsername);
            $this->yearlyRepository->updateTablename($oldUsername, $changedUsername);
            $this->wdRepository->updateTablename($oldUsername, $changedUsername);
            return header('Location: ./?route=userSettings');
        } elseif(isset($_POST['changedPassword'])) {
            $currentPassword = @(string) $_POST['currentPassword'];
            $changedPassword = @(string) $_POST['changedPassword'];
            $changedPasswordRepeat = @(string) $_POST['changedPassword2'];
            $errorArray = $this->validatePassword($currentPassword, $changedPassword, $changedPasswordRepeat);
            if(!empty($errorArray)) {
                $_SESSION['errorArray'] = implode(',', $errorArray);
                return header('Location: ./?route=userSettings');
            }
            $passwordHashed = password_hash($changedPassword, PASSWORD_DEFAULT, ['cost' => 12]);  
            $this->usersRepository->updatePassword($_SESSION['username'], $passwordHashed);
            return header('Location: ./?route=userSettings');
        } elseif (isset($_POST['edit'])) {
            $example = array_keys($_POST)[0];
            $catType = '';
            if(preg_match('/^.*rev.*$/', $example)) {
                $catType = 'revcat';
            } elseif (preg_match('/^.*exp.*$/', $example)) {
                $catType = 'expcat';
            }      
            $cats = [];
            for($i = 1; $i < 11; $i++) {
                $cats["{$catType}{$i}"] = $_POST["{$catType}{$i}"];
            }
            $oldcats = [];
            for($i = 1; $i < 11; $i++) {
                $oldcats["old{$catType}{$i}"] = $_POST["old{$catType}{$i}"];
            }
            $errorArray = $this->validateCategory($oldcats, $cats);
            if(!empty($errorArray)) {
                $_SESSION['errorArray'] = implode(',', $errorArray);
                return header('Location: ./?route=userSettings');
            }
            $changeCats = [];
            for ($i = 0; $i < 10; $i++) {
                if(array_values($oldcats)[$i] !== array_values($cats)[$i]) $changeCats[array_keys($cats)[$i]] = array_values($cats)[$i];
            }
            if(!empty($changeCats)) $this->usersRepository->updateCategories(array_keys($changeCats), array_values($changeCats));
            $changeEntries = [];
            for ($i = 0; $i < 10; $i++) {
                if(array_values($oldcats)[$i] !== array_values($cats)[$i] && array_values($cats)[$i] !== '') $changeEntries[array_values($oldcats)[$i]] = array_values($cats)[$i];
                if(array_values($oldcats)[$i] !== array_values($cats)[$i] && array_values($cats)[$i] === '') {
                    $z = $i+1;
                    $deleteCategory["{$catType}{$z}"] = array_values($oldcats)[$i];
                    $this->entryRepository->deleteEntryCategory($deleteCategory);
                }
            }
            if(!empty($changeEntries)) $this->entryRepository->updateEntryCategory($changeEntries);
            return header('Location: ./?route=userSettings');
        } elseif (isset($_POST['delete'])) {
            $example = array_keys($_POST)[1];
            $catType = '';
            if(preg_match('/^.*rev.*$/', $example)) {
                $catType = 'revcat';
            } elseif (preg_match('/^.*exp.*$/', $example)) {
                $catType = 'expcat';
            }   
            $deleteCategory = [];
            for ($i = 1; $i < 11; $i++) {
                if(isset($_POST["delete{$catType}{$i}"])) $deleteCategory["{$catType}{$i}"] = $_POST["delete{$catType}{$i}"];
            }
            if(array_values($deleteCategory) === '') return header('Location: ./?route=userSettings');
            $this->usersRepository->deleteCategory($deleteCategory);
            $this->entryRepository->deleteEntryCategory($deleteCategory);
            return header('Location: ./?route=userSettings');
        }
    }

    public function validateUsername($username) {
        $errorArray = [];
        if(!preg_match('/^.{4,30}$/', $username)) $errorArray[] = "- username must include 4 to 30 characters";
        if(preg_match('/^\d.*?/', $username)) $errorArray[] = "- username can`t begin with a number";
        if(preg_match('/[\.\^\$\*\+\-\?\(\)\[\]\{\}\\\|]/', $username)) $errorArray[] = "- you can`t use: . ^ $ * + - ? ( ) [ ] { } \ |";
        $usernames = $this->fetchUsernames();
        foreach($usernames AS $name) {
            if($username === $name) $errorArray[] = "- username already exists";
        }
        return $errorArray;
    }

    public function validatePassword($currentPassword, $changedPassword, $changedPasswordRepeat) {
        $errorArray = [];
        $user = $this->usersRepository->fetchUser($_SESSION['username']);
        if(!password_verify($currentPassword, $user->password)) $errorArray[] = "- current password is wrong";
        if($changedPassword !== $changedPasswordRepeat) $errorArray[] = "- new passwords don't match";
        if(!preg_match('/^.{6,60}$/', $changedPassword)) $errorArray[] = "- new password must include 8 to 60 characters";
        if(!preg_match('/^.*(?=.*[a-z])[A-Za-z\d@$!%*?&].*$/', $changedPassword)) $errorArray[] = "- new password must include at least one lowercase letter";
        if(!preg_match('/^.*(?=.*[A-Z])[A-Za-z\d@$!%*?&].*$/', $changedPassword)) $errorArray[] = "- new password must include at least one uppercase letter";
        if(!preg_match('/^.*(?=.*\d)[A-Za-z\d@$!%*?&].*$/', $changedPassword)) $errorArray[] = "- new password must include at least one number";
        if(!preg_match('/^.*(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].*$/', $changedPassword)) $errorArray[] = "- new password must include at least one special character";
        if(!preg_match('/^.*[^\ ].*[^\ .].*$/', $changedPassword)) $errorArray[] = "- new password can't start or end with blank space";
        return $errorArray;     
    }

    public function validateCategory($oldcats, $cats) {
        $errorArray = [];
        $categoryCounts = array_count_values($cats);
        foreach($categoryCounts as $key => $value) {
            if($value > 1 && $key !== '') $errorArray[] = "- can't choose ${key} again";
        }
        foreach($cats as $key => $value) {
            $catname = preg_replace("/.*(\d{1,2})/","Category $1", $key);
            if(!preg_match('/^.{3,20}$/', $value) && $value !== '') $errorArray[] = "- category name of ${catname}: \"${value}\" must include 3 to 20 symbols";
        }
        return $errorArray;   
    }

    public function fetchUserCats() {
        $username = $_SESSION["username"];
        $userData = $this->usersRepository->fetchAll($username);
        $revCategories = [];
        $expCategories = [];
        $wdCategories = [];
        $userCats = [];
        foreach($userData AS $data) {
            foreach($data AS $key => $value) {
                if(preg_match('/^revcat\d{1,2}/', $key)) {
                    $revCategories[] = $value;
                }
                if(preg_match('/^expcat\d{1,2}/', $key)) {
                    $expCategories[] = $value;
                }
                if(preg_match('/^wd\d{1,2}/', $key)) {
                    $wdCategories[] = $value;
                }
            }
        }
        $userCats['revcats'] = $revCategories;
        $userCats['expcats'] = $expCategories;
        $userCats['wdcats'] = $wdCategories;
        return $userCats;
    }
}