<?php 

namespace App\Controller;

use App\Yearly\YearlyRepository;

class YearlyController extends AbstractController {
    public function __construct(
        protected YearlyRepository $yearlyRepository) {}

    public function generateAndFillTableYearly($username, $donationgoal, $savinggoal, $totalwealthgoal) {
        $this->yearlyRepository->generateTable($username);
        $year = date('Y');
        $this->yearlyRepository->add($username, $year, $donationgoal, $savinggoal, $totalwealthgoal);
    }

    public function fetchCurrentGoals($year) {
        $username = $_SESSION['username'];
        $goalsMapRaw = $this->yearlyRepository->fetchAllOfYear($username, $year);
        if(!empty($goalsMapRaw)) {
            $goalsMap = array_slice($goalsMapRaw[0], 2, sizeof($goalsMapRaw[0]) - 2);
            return $goalsMap;
        } else {
            return ["donationgoal" => 0, "savinggoal" => 0, "totalwealthgoal" => 0];
        }
        
    }
}