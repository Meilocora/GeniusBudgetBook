<?php 

namespace App\Controller;

use App\WealthDistribution\WDRepository;
use App\Users\UsersRepository;

class WDController extends AbstractController{

    public function __construct(
        protected WDRepository $wdRepository,
        protected UsersRepository $usersRepository) {}

    public function wdCategoriesOfMonth($username, $date): array {
        $wdArray = $this->wdRepository->fetchAllOfMonth($username, $date);
        if(empty($wdArray)) {
            $pastmonth = date('Y-m', strtotime($date. ' -1 months'));
            $wdArray = $this->wdRepository->fetchAllOfMonth($username, $pastmonth);
        }
        if(!empty($wdArray)) {
            $wdcategoriesraw = array_slice($wdArray[0], 2, sizeof($wdArray[0]) - 2);
            $wdcategories = [];
            $valueArray = array_values($wdcategoriesraw);
            $keyArray = array_keys($wdcategoriesraw);
            for($x = 0; $x < sizeof($keyArray); $x = $x+2) {
                $wdcategories[] = preg_replace('/\-\d{1}.*/', '', $keyArray[$x]);
                $wdcategories[] = $valueArray[$x];
                $wdcategories[] = $valueArray[$x+1];
            }
            return $wdcategories;  // [name, target-value, actual-value, ...] 
        } else {
            $results = $this->usersRepository->fetchWDCategories($username);
            $resultsIndexed = array_values($results);
            $wdcategories = [];
            for($x = 0; $x <= sizeof($resultsIndexed)-1; $x=$x+2) {
                $wdcategories[] = $resultsIndexed[$x];
                $wdcategories[] = 0;
                $wdcategories[] = 0;
            }  
            return $wdcategories;   // [name, target-value, actual-value, ...] 
        }
    }

    public function updateBalances() {
        $username = strtolower($_SESSION['username']);
        $date = date('Y-m',strtotime($_POST['date']));
        unset($_POST['date']);
        $wdArray = $this->wdRepository->fetchAllOfMonth($username, $date);
        if(!empty($wdArray)) {
            $wdcategoriesraw = array_slice($wdArray[0], 2, sizeof($wdArray[0]) - 2);
            $wdUpdateArray = array_combine(array_keys($wdcategoriesraw), array_values($_POST));
            $this->wdRepository->update($username, $date, $wdUpdateArray);
            header('Location: ./?route=monthly-page');
        } else {
            $results = $this->usersRepository->fetchWDCategories($username);
            $resultsIndexed = array_values($results);
            $wdcategories = [];
            for($x = 0; $x <= sizeof($resultsIndexed)-1; $x=$x+2) {
                $wdcategories[] = $resultsIndexed[$x] . '-' . $resultsIndexed[$x+1] . '-target';
                $wdcategories[] = $resultsIndexed[$x] . '-' . $resultsIndexed[$x+1] . '-actual';
            }  
            $wdCreateArray = array_combine($wdcategories, array_values($_POST));
            $this->wdRepository->create($username, $date, $wdCreateArray);
            header('Location: ./?route=monthly-page');
        }
    }
}