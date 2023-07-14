<?php


$inn = '';
$today = date("Y-m-d");
$dateTo = date("Y-m-d");
$dateFrom = date("Y-m-d", strtotime($dateTo . '- 7 days'));
$message = '';
$status = 0;

if (isset($_POST)) {
    if (isset($_POST['dateFrom'])) {
        $dateFrom = $_POST['dateFrom'];
    }
    if (isset($_POST['dateTo'])) {
        $dateTo = $_POST['dateTo'];
    }
    if ($dateFrom > $dateTo) {
        $status = 1;
        $message = 'Начальная дата не может быть больше конечной!';
        $dateFrom = $dateTo;
    }
    if (isset($_POST['organization'])) {
        $inn = $_POST['organization'];
    }
    if (isset($_POST['category'])) {
        $category = $_POST['category'];
    }
    if (isset($_POST['opiuDate'])) {
        $opiuDate = $_POST['opiuDate'];
        $month = date("m", strtotime($opiuDate));
        $year = date('Y', strtotime($opiuDate));
        $dateFrom = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
        $dateTo = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));
    }
}

$groupData = getGroupData($dateFrom, $dateTo, $inn);
$allData = getAllData($dateFrom, $dateTo, $inn);
