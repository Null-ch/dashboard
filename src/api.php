<?php
function getAllData($date1, $date2)
{
    if (!$date1) {
        $date1 = '2023-03-01';
    }
    if (!$date2) {
        $date2 = '2023-03-20';
    }

    $headers = array('Content-Type:application/json', 'Authorization: Basic ' . base64_encode("ПользовательРМ:13579"));
    $date = [
    'DateFrom' => "$date1",
    'DateTo' => "$date2"
    ];
    $dataString = json_encode($date);

    $url = 'http://185.60.133.5:47800/uat_test3/hs/mj_api/getReport';
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch), true);
    $result = (array) $result;
    $result = $result['Data'];
    return $result;
}
