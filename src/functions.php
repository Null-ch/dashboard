<?php


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
