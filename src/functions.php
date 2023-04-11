<?php
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
function check_auth(): bool
{
    return !!($_SESSION['user_id'] ?? false);
}

function getProfit()
{
    global $pdo;
    $data = $pdo->query("SELECT manage, agent, nomenclature, SUM(profit) AS all_profit FROM base GROUP BY nomenclature");
    $count = [];
    while ($row = $data->fetch()) {
        $count[] = $row['all_profit'];
    }
    return $count;
}



function getManageTnKm()
{
    global $pdo;
    $data = $pdo->query("SELECT manage, AVG(profit_km) AS avg_profit FROM base GROUP BY manage");
    $manageInfo = [];
    while ($row = $data->fetch()) {
        $manageInfo[$row['manage']] = $row['avg_profit'];
    }
    return $manageInfo;
}
