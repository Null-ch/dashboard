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
function array_combine_($keys, $values)
{
    $result = array();

    foreach ($keys as $i => $k) {
        $result[$k][] = $values[$i];
    }

    array_walk(
        $result, function (&$v) {
            $v = (count($v) == 1) ? array_pop($v) : $v;
        }
    );

    return $result;
}

function getAvgTnPerKm($arr)
{
    $name = '';
    $base = [];
    foreach ($arr as $val) {
        foreach ($val as $key => $value) {
            if ($key == 'Manager') {
                $name = $value;
                $manage[] = $value;
            }
            if ($key == 'ProfitTnPerKm') {
                $prof[] = $value;
            }
        }
        $count = array_count_values($manage);
    }
    $res = array_combine_($manage, $prof);
    foreach ($res as $key => $val) {
        $total[$key] = array_sum($val) / $count[$key];
    }
    return $total;
}

function getName($arr)
{
    foreach ($arr as $key => $val) {
        $manageNamesChart[] = $key;
    }
    $manageChart = '';
    foreach ($manageNamesChart as $val) {
        $manageChart .= "'$val',";
    }
    return $manageChart;
}
function getValue($arr)
{

    foreach ($arr as $key => $val) {
        $manageStatsChart[] = $val;
    }
    $manageStatsChart = implode(',', $manageStatsChart);
    return $manageStatsChart;
}

function getNomenclature($arr)
{
    $total = [];
    foreach ($arr as $val) {
        foreach ($val as $key => $value) {
            if ($key == 'Product') {
                $manage[] = $value;
            }
            if ($key == 'ProfitPerDelivery') {
                $prof[] = $value;
            }
        }
    }
    $res = array_combine_($manage, $prof);
    foreach ($res as $key => $val) {
        if (is_array($val)) {
            $total[$key] = array_sum($val);
        } else {
            $total[$key] = $val;
        }
    }
    return $total;
}

function getManageCount($arr)
{
    $name = '';
    $base = [];
    foreach ($arr as $val) {
        foreach ($val as $key => $value) {
            if ($key == 'Manager') {
                $name = $value;
                $manage[] = $value;
            }
        }
    }
  $count = array_count_values($manage);
    return $count;
}