<?php
function getPlanProfit($arr)
{
    foreach ($arr as $val) {
        foreach ($val as $key => $value) {
            if ($key == 'Менеджер') {
                $name = $value;
                $manage[] = $value;
            }
            if ($key == 'План') {
                $prof[] = $value;
            }
        }
    }
    $res = array_combine_($manage, $prof);
    foreach ($res as $key => $item) {
        if (is_array($item)) {
            $profit = array_unique($item);
            $data["$key"] = "$profit[0]";
        } else {
            $data["$key"] = "$item";
        }
    }

    return $data;
}
function getProfit($arr)
{
    foreach ($arr as $val) {
        foreach ($val as $key => $value) {
            if ($key == 'Менеджер') {
                $name = $value;
                $manage[] = $value;
            }
            if ($key == 'ПрибыльРейс') {
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
function getPercent($plan, $arrTotal)
{
    foreach ($arrTotal as $key => $item) {
        if ($item != 0) {
            $result[$key] = round((($item / $plan[$key]) * 100), 2);
        } else {
            $result[$key] = 0;
        }
    }
    return $result;
}

function getPercentData($percent, $profit)
{
    foreach ($profit as $key => $item) {
        $result[$key] = "{$item} ({$percent[$key]}%)";
    }
    return $result;
}

function getSortBy($example, $sortedArr)
{
    foreach ($example as $key => $item) {
        $result[$key] = $sortedArr[$key];
    }
    return $result;
}

function get_sum($arr)
{
    $sum = 0;
    foreach ($arr as $elem)
        $sum += $elem;
    return $sum;
}
function array_combine_($keys, $values)
{
    $result = array();

    foreach ($keys as $i => $k) {
        $result[$k][] = $values[$i];
    }

    array_walk(
        $result,
        function (&$v) {
            $v = (count($v) == 1) ? array_pop($v) : $v;
        }
    );

    return $result;
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
function getAvgTnPerKm($arr)
{
    foreach ($arr as $val) {
        foreach ($val as $key => $value) {
            if ($key == 'Менеджер') {
                $name = $value;
                $manage[] = $value;
            }
            if ($key == 'ПрибыльТнКм') {
                $prof[] = $value;
            }
        }
        $count = array_count_values($manage);
    }
    $res = array_combine_($manage, $prof);
    foreach ($res as $key => $val) {
        if (is_array($val)) {
            $total[$key] = array_sum($val) / $count[$key];
        } else {
            $total[$key] = $val / $count[$key];
        }
    }
    return $total;
}

function getNomenclature($arr)
{
    $total = [];
    foreach ($arr as $val) {
        foreach ($val as $key => $value) {
            if ($key == 'Контрагент') {
                $manage[] = $value;
            }
            if ($key == 'ПрибыльРейс') {
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
            if ($key == 'Менеджер') {
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
/*Старые функции для получения данных из БД
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
*/