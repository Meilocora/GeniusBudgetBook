<?php 

namespace App\Controller;

use App\WealthDistribution\WDRepository;
use App\Users\UsersRepository;

class WDController extends AbstractController{

    public function __construct(
        protected WDRepository $wdRepository,
        protected UsersRepository $usersRepository) {}
        #TODO: username als constructor festlegen, genau wie aktuelles Datum

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

    public function currentTotalWealthDistribution($queryDate) {
        $username = $_SESSION['username'];
        $wdMapraw = $this->wdRepository->fetchAllOfMonth($username, $queryDate);
        if(!empty($wdMapraw)) {
            $wdMap = array_slice($wdMapraw[0], 2, sizeof($wdMapraw[0]) - 2);
            return $wdMap; 
        } else {
            return [0];
        }
    }

    public function currentWDTarget($queryDate) {
        $wdMap = $this->currentTotalWealthDistribution($queryDate);
        $currentWDTargetArray = [];
        foreach($wdMap AS $key => $value) {
            if(preg_match('/^.*target$/', $key)) $currentWDTargetArray[preg_replace('/\-\d{1}.*/', '', $key)] = $value;
        }
        arsort($currentWDTargetArray);
        return $currentWDTargetArray;
    }

    public function currentWDActual($queryDate) {
        $wdMap = $this->currentTotalWealthDistribution($queryDate);
        $currentWDActualArray = [];
        foreach($wdMap AS $key => $value) {
            if(preg_match('/^.*actual$/', $key)) $currentWDActualArray[preg_replace('/\-\d{1}.*/', '', $key)] = $value;
        }
        arsort($currentWDActualArray);
        return $currentWDActualArray;
    }

    public function currentTotalWealth($queryDate) {
        $username = $_SESSION['username'];
        $wdMapraw = $this->wdRepository->fetchAllOfMonth($username, $queryDate);
        if(!empty($wdMapraw)) {
            $wdMap = array_slice($wdMapraw[0], 2, sizeof($wdMapraw[0]) - 2);
            $wdValues = array_values($wdMap);
            $currentTotalWealth = 0;
            for($x=1; $x<sizeof($wdValues); $x=$x+2) {
                $currentTotalWealth += $wdValues[$x];
            }
            return $currentTotalWealth;
        } else {
            return 0;
        }
    }

    public function wdTrend($queryDate, $startDate, $dataSet) {
        $username = $_SESSION['username'];
        $endDate = $queryDate . '-01';
        $wdCollectionraw = $this->wdRepository->fectAllForTimeInterval($username, $startDate, $endDate);
        $wdCollection = [];
        foreach($wdCollectionraw AS $array) {
            $wdCollection[] = array_slice($array, 1, sizeof($array) - 1);
        } 
        $filteredArray = [];
        switch ($dataSet) {
            case 'actual':
                foreach($wdCollection AS $array) {
                    $localeArray = [];
                    foreach($array AS $key => $value) {
                        if($key === 'dateslug') $localeArray[$key] = $value;
                        if(preg_match('/^.*actual$/', $key)) $localeArray[preg_replace('/\-\d{1}.*/', '', $key)] = $value;
                    }
                $filteredArray[] = $localeArray;
                }
                return $filteredArray;
            case 'total-target-actual':
                foreach($wdCollection AS $array) {
                    $localeArray = [];
                    $localSumTarget = 0;
                    $localSumActual = 0;
                    foreach($array AS $key => $value) {
                        if($key === 'dateslug') $localeArray[$key] = $value;
                        if(preg_match('/^.*target$/', $key)) $localSumTarget += (int) $value;
                        if(preg_match('/^.*actual$/', $key)) $localSumActual += (int) $value;
                    }
                $localeArray['Total wealth target'] = $localSumTarget;
                $localeArray['Total wealth actual'] = $localSumActual;
                $filteredArray[] = $localeArray;
                }
                return $filteredArray;
            case 'actual-liquid':
                foreach($wdCollection AS $array) {
                    $localeArray = [];
                    foreach($array AS $key => $value) {
                        if($key === 'dateslug') $localeArray[$key] = $value;
                        if(preg_match('/^.*-1-actual$/', $key)) $localeArray[preg_replace('/\-1\-actual.*/', '', $key)] = $value;
                    }
                $filteredArray[] = $localeArray;
                }
                return $filteredArray;
        }
    }
}