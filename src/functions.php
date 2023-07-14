<?php

function getLogisticData($date1 = '', $date2 = '', $inn = '', $method = 'logisticsReport')
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
        "tractorCode" => "00503",
        "trailerCode" => "00509",
    ];
    $dataString = json_encode($date);

    $url = '185.60.133.5:47800/UATProf/hs/googleDashboard/' . $method;
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    set_time_limit(0);

    $result = json_decode(curl_exec($ch), true);
    return $result;
}


function traceWithCargo($data)
{
    $traceWithCargo = [];
    $item = '';
    foreach ($data as $value) {
        $item = $value['ТС'];
        if ((float)$value['ПробегСГр'] != 0) {
            $traceWithCargo[$item] = 0;
            $traceWithCargo[$item] = (float)$value['ПробегСГр'];
        }
    }
    return $traceWithCargo;
}
function getGsm($data)
{
    $gsm = [];
    $item = '';
    foreach ($data as $value) {
        $item = $value['ТС'];
        if ((float)$value['ГСМ'] != 0) {
            $gsm[$item] = 0;
            $gsm[$item] = (float)$value['ГСМ'];
        }
    }
    return $gsm;
}

function getRepair($data)
{
    $repair = [];
    $item = '';
    foreach ($data as $value) {
        $item = $value['ТС'];
        if ((float)$value['Запчасти'] != 0) {
            $repair[$item] = 0;
            $repair[$item] = (float)$value['Запчасти'];
        }
    }
    return $repair;
}

function getWheels($data)
{
    $wheels = [];
    $item = '';
    foreach ($data as $value) {
        $item = $value['ТС'];
        if ((float)$value['Шины'] != 0) {
            $wheels[$item] = 0;
            $wheels[$item] = (float)$value['Шины'];
        }
    }
    return $wheels;
}

function getCarNumber($gsm = '', $traceWithCargo = '', $repair = '', $wheels = '')
{
    $numbers = [];
    if ($gsm) {
        foreach ($gsm as $key => $value) {
            $numbers['ГСМ'][$key] = $key;
        }
    }
    if ($traceWithCargo) {
        foreach ($traceWithCargo as $key => $value) {
            $numbers['ПробегСГр'][$key] = $key;
        }
    }
    if ($repair) {
        foreach ($repair as $key => $value) {
            $numbers['Запчасти'][$key] = $key;
        }
    }
    if ($wheels) {
        foreach ($wheels as $key => $value) {
            $numbers['Шины'][$key] = $key;
        }
    }

    return $numbers;
}

function getTrailerNumber($repair = '', $wheels = '')
{
    $numbers = [];
    if ($repair) {
        foreach ($repair as $key => $value) {
            $numbers['Запчасти'][$key] = $key;
        }
    }
    if ($wheels) {
        foreach ($wheels as $key => $value) {
            $numbers['Шины'][$key] = $key;
        }
    }

    return $numbers;
}

function allSpentPerCar($numbers, $gsm, $traceWithCargo, $repair, $wheels)
{
    $totalResult = [];
    foreach ($numbers as $key => $value) {
        if ($key == 'ПробегСГр') {
            foreach ($value as $val) {
                $totalResult[$val] = 0;
                if (isset($traceWithCargo[$val])) {
                    $totalResult[$val] += $traceWithCargo[$val];
                }
            }
        }
        if ($key == 'ГСМ') {
            foreach ($value as $val) {
                $totalResult[$val] = 0;
                if (isset($gsm[$val])) {
                    $totalResult[$val] += $gsm[$val];
                }
            }
        }
        if ($key == 'Запчасти') {
            foreach ($value as $val) {
                $totalResult[$val] = 0;
                if (isset($repair[$val])) {
                    $totalResult[$val] += $repair[$val];
                }
            }
        }
        if ($key == 'Шины') {
            foreach ($value as $val) {
                $totalResult[$val] = 0;
                if (isset($wheels[$val])) {
                    $totalResult[$val] += $wheels[$val];
                }
            }
        }
    }
    return $totalResult;
}

function allSpentPerTrailer($numbers, $repair, $wheels)
{
    $totalResult = [];
    foreach ($numbers as $key => $value) {
        if ($key == 'Запчасти') {
            foreach ($value as $val) {
                $totalResult[$val] = 0;
                if (isset($repair[$val])) {
                    $totalResult[$val] += $repair[$val];
                }
            }
        }
        if ($key == 'Шины') {
            foreach ($value as $val) {
                $totalResult[$val] = 0;
                if (isset($wheels[$val])) {
                    $totalResult[$val] += $wheels[$val];
                }
            }
        }
    }
    return $totalResult;
}

function normalize($arr)
{
    $result = [];
    foreach ($arr as $key => $value) {
        $result[] = ['ТС' => $key, 'Значение' => $value];
    }
    return $result;
}
function allSpent($data)
{
    $gsmTruck = 0;
    $wheelsTruck = 0;
    $repairTruck = 0;

    $wheelsTrailer = 0;
    $repairTrailer = 0;
    if (isset($data['dataTracktor'])) {
        foreach ($data['dataTracktor'] as $value) {
            $repairTruck += (float)$value['Запчасти'];
            $gsmTruck += (float)$value['ГСМ'];
            $wheelsTruck += (float)$value['Шины'];
        }
    }
    if (isset($data['dataTrailer'])) {
        foreach ($data['dataTrailer'] as $value) {
            $repairTrailer += (float)$value['Запчасти'];
            $wheelsTrailer += (float)$value['Шины'];
        }
    }
    return ['Шины (тягачи)' => $wheelsTruck, 'ГСМ (тягачи)' => $gsmTruck, 'Запчасти (тягачи)' => $repairTruck, 'Шины (прицепы)' => $wheelsTrailer, 'Запчасти (прицепы)' => $repairTrailer];
}
function trueDataLogistic($arr, $dataType)
{
    $data = [];
    foreach ($arr[$dataType] as $item) {
        if ($item['КолРейсов'] > 0) {
            $data[] = $item;
        }
    }
    return $data;
}

function trueDataDash($arr)
{
    $data = [];
    if (is_array($arr)) {
        foreach ($arr as $key => $item) {
            if ($item != 0) {
                $data[$key] = $item;
            }
        }
        return $data;
    } else {
        return $arr;
    }
}

function getArticles($arr)
{
    $res = [];
    foreach ($arr as $val) {
        foreach ($val as $key => $item) {
            if ($key == 'СтатьяДДС' && $item) {
                $res[$item] = $item;
            } elseif ($key == 'СтатьяДДС' && !$item) {
                $res[$item] = 'Без категории';
            }
        }
    }
    $res = array_unique($res);
    return $res;
}
function getPastMounth($month = '')
{
    if ($month == '') {
        $today = date("Y-m");
        $year = date('Y', strtotime($today));
        $month = date("m", strtotime($today));
        $dateFrom = date('Y-m-d', mktime(0, 0, 0, $month - 1, 1, $year));
        $dateTo = date('Y-m-t', mktime(0, 0, 0, $month - 1, 1, $year));
    } else {
        $year = date('Y', strtotime($month));
        $month = date("m", strtotime($month));
        $dateFrom = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
        $dateTo = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));
    }
    return ['dateFrom' => $dateFrom, 'dateTo' => $dateTo];
}
function getMoneyMovement($arr)
{
    $name = [];
    $result = [];
    $finalArr = [];

    // Считаем расход/доход
    foreach ($arr as $val) {
        foreach ($val as $key => $value) {
            if ($key == 'СтатьяДДС' && $value) {
                $name[] = $value;
            } elseif ($key == 'СтатьяДДС' && !$value) {
                $name[] = 'Без категории';
            }
            if ($key == 'Сумма') {
                $total[] = $value;
            }
        }
    }

    //Собираем все в 1 массив в виде Название статьи => массив с суммами по данной статье
    foreach ($name as $i => $k) {
        $result[$k][] = $total[$i];
        $finalArr[$k] = 0;
    }

    //Преобразуем это к одномерному массиву вида Название статьи => Сумма
    foreach ($result as $key => $val) {
        if (is_array($val)) {
            foreach ($val as $item) {
                $finalArr[$key] += abs($item);
            }
        } else {
            $finalArr[$key] = $val;
        }
    }
    if ($finalArr['Без категории'] === 0) {
        unset($finalArr['Без категории']);
    }
    return $finalArr;
}
function getTotalApiu($arr)
{
    $start = number_format(round($arr[0]['ОстатокДСНаНачало']), 0, '', ' ');
    $end = number_format(round($arr[0]['ОстатокДСНаКонец']), 0, '', ' ');
    $total = $arr[0]['ОстатокДСНаКонец'] - $arr[0]['ОстатокДСНаНачало'];
    $total = number_format($total, 0, '', ' ');
    $res = "Остаток на начало периода: {$start} руб., Остаток на конец периода: {$end} руб., Оборот: {$total} руб.";
    return $res;
}
function getIncome($arr, $category = '')
{
    $income = [];
    if (!$category) {
        foreach ($arr as $val) {
            if ($val['Операция'] == 'Поступление') {
                $income[] = $val;
            }
        }
    } else {
        foreach ($arr as $val) {
            if ($val['Операция'] == 'Поступление' && (in_array(($val['СтатьяДДС']), $category) || ($val['СтатьяДДС'] == '' && in_array('Без категории', $category)))) {
                $income[] = $val;
            }
        }
    }
    return $income;
}
function getExpense($arr, $category)
{
    $expense = [];
    if (!$category) {
        foreach ($arr as $val) {
            if ($val['Операция'] == 'Выбытие') {
                $expense[] = $val;
            }
        }
    } else {
        foreach ($arr as $val) {
            if ($val['Операция'] == 'Выбытие' && (in_array(($val['СтатьяДДС']), $category) || ($val['СтатьяДДС'] == '' && in_array('Без категории', $category)))) {
                $expense[] = $val;
            }
        }
    }
    return $expense;
}
function getPlanProfit($arr)
{
    foreach ($arr as $val) {
        foreach ($val as $key => $value) {
            if ($key == 'Менеджер') {
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
function getTripCount($arr)
{
    foreach ($arr as $val) {
        foreach ($val as $key => $value) {
            if ($key == 'Менеджер') {
                $name = $value;
                $manage[] = $value;
            }
            if ($key == 'КолРейсов') {
                $prof[] = $value;
            }
        }
    }
    $res = array_combine_($manage, $prof);
    return $res;
}
function getProfit($arr)
{
    foreach ($arr as $val) {
        foreach ($val as $key => $value) {
            if ($key == 'Менеджер') {
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

function getSortBy($example, $sortedArr)
{
    foreach ($example as $key => $item) {
        $result[$key] = $sortedArr[$key];
    }
    return $result;
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
    $manageNamesChart = [];
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
                $manage[] = $value;
            }
            if ($key == 'ПрибыльТнКм') {
                $prof[] = $value;
            }
        }
    }
    $res = array_combine_($manage, $prof);
    return $res;
}

function getProfitPerClient($arr)
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

    foreach ($arr as $val) {
        foreach ($val as $key => $value) {
            if ($key == 'Менеджер') {
                $manage[] = $value;
            }
        }
    }
    $count = array_count_values($manage);
    return $count;
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