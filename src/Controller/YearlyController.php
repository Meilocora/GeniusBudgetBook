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
        $goalsMapRaw = $this->yearlyRepository->fetchAllOfYear($year);
        if(!empty($goalsMapRaw)) {
            $goalsMap = array_slice($goalsMapRaw[0], 2, sizeof($goalsMapRaw[0]) - 2);
            return $goalsMap;
        } else {
            return ["donationgoal" => 0, "savinggoal" => 0, "totalwealthgoal" => 0];
        }
    }

    public function setGoals() {
        $username = strtolower($_SESSION['username']);
        $year = $_POST['year'];
        $totalwealthgoal = isset($_POST['totalwealthgoal']) ? $_POST['totalwealthgoal'] : 0;
        $donationgoal = isset($_POST['donationgoal']) ? $_POST['donationgoal'] : 0;
        $savinggoal = isset($_POST['savinggoal']) ? $_POST['savinggoal'] : 0;
        $setGoals = $this->yearlyRepository->fetchAllOfYear($year);
        if(!empty($setGoals)) {
            $this->yearlyRepository->update($year, $donationgoal, $savinggoal, $totalwealthgoal);
        } else {
            $this->yearlyRepository->add($username, $year, $donationgoal, $savinggoal, $totalwealthgoal);
        }
        return;
    }
}