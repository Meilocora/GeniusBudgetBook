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

    public function fetchCurrentGoals() {
        $username = $_SESSION['username'];
        $year = date('Y');
        $goalsMapRaw = $this->yearlyRepository->fetchAllOfYear($username, $year);
        $goalsMap = array_slice($goalsMapRaw[0], 2, sizeof($goalsMapRaw[0]) - 2);
        return $goalsMap;
    }
}