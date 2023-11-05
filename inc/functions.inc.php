<?php

function e($html) {
    return htmlspecialchars($html, ENT_QUOTES, 'UTF-8', true);
}

// Needs two timestamps ... @strtotime(...);
function echoDate( $start, $end ){
    $current = $start;
    $ret = array();
    while( $current<$end ){
        $next = @date('Y-M-01', $current) . "+1 month";
        $current = @strtotime($next);
        $ret[] = date('M Y', $current);
    }
    array_unshift($ret, date('M Y', $start));
    array_pop($ret);
    return $ret;
}

function calculatePercentagesArray($array) {
    $sum = array_sum($array);
    if($sum !== 0) {
        $percentagesArray= [];
        foreach($array AS $key => $value) {
            $percentagesArray[$key] = round($value/$sum*100, 2);
        }
        return $percentagesArray;
    } else {
        return [0, 0];
    }
    
}

function calculateRemainingDays($year) {
    $yearEnd = strtotime("31 December ${year}");
    $today = strtotime(date($year . '-m-d'));
    $timeleft = $yearEnd-$today;
    $daysleft = round((($timeleft/24)/60)/60); 
    return $daysleft;
}

function calculateTimespanDays($startDate, $endDate) {
    $timespan = strtotime($endDate) - strtotime($startDate);
    return round((($timespan/24)/60)/60); 
}
