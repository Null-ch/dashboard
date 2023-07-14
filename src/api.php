<?php
function getAllData($date1 = '', $date2 = '', $inn = '', $method = 'getReport', $dataType = 'Data')
{
    if (!$date2) {
        $date2 = date("Y-m-d");
    }
    if (!$date1) {
        $date1 = date("Y-m-d", strtotime($date2 . '- 7 days'));
    }
    if (empty($inn)) {
        $inn = "5024163243";
    }

    $headers = array('Content-Type:application/json', 'Authorization: Basic ' . base64_encode("СервисGoogleDashboard://GDS%321"));
    $date = [
        'dateFrom' => "$date1",
        'dateTo' => "$date2",
        "organizationITN" => "$inn",
    ];
    $dataString = json_encode($date);

    $url = '185.60.133.5:47800/UATProf/hs/googleDashboard/' . $method;
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch), true);
    $result = (array) $result;
    $result = $result[$dataType];
    return $result;
}

function getGroupData($date1 = '', $date2 = '', $inn = '', $method = 'getReport', $dataType = 'groupData')
{
    if (!$date2) {
        $date2 = date("Y-m-d");
    }
    if (!$date1) {
        $date1 = date("Y-m-d", strtotime($date2 . '- 7 days'));
    }
    if (!$inn) {
        $inn = "5024163243";
    }

    $headers = array('Content-Type:application/json', 'Authorization: Basic ' . base64_encode("СервисGoogleDashboard://GDS%321"));
    $date = [
        'dateFrom' => "$date1",
        'dateTo' => "$date2",
        "organizationITN" => "$inn",
    ];
    $dataString = json_encode($date);

    $url = '185.60.133.5:47800/UATProf/hs/googleDashboard/' . $method;
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch), true);
    $result = (array) $result;
    $result = $result[$dataType];
    return $result;
}
