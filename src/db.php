<?php


function pdo(): PDO
{
$host = '31.31.196.203';
$db = 'u1999593_default';
$user = 'u1999593_default';
$pass = 'IUix4Mbiv85L2kTK';
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