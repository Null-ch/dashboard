<?php

// function pdo(): PDO
// {
//     $host = '31.31.196.171';
//     $db = 'u2016645_dashboard';
//     $user = 'u2016645_default';
//     $pass = 'XOooT47K2zPdR451';
//     $charset = 'utf8';

//     $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
//     $opt = [
//         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//     ];
//     static $pdo;

//     if (!$pdo) {
//         $pdo = new PDO($dsn, $user, $pass, $opt);
//     }
//     return $pdo;
// }

function pdo($host = '127.0.0.1', $db = 'u2016645_dashboard', $user = 'root', $pass = ''): PDO
{
    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    static $pdo;

    if (!$pdo) {
        $pdo = new PDO($dsn, $user, $pass, $opt);
    }
    return $pdo;
}