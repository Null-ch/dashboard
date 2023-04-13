<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/db.php';

function login($email, $password)
{
    $response = 0;
    $password_hash = hash('sha256', $password);
    $query = pdo()->prepare("SELECT * FROM users WHERE email = :email");
    $params = ['email' => $email];
    $query->execute($params);
    if ($query->rowCount() > 0) {
        $getRow = $query->fetch(PDO::FETCH_ASSOC);
    } else {
        return $response;
    }

    if ($getRow['email'] != $email || $getRow['password'] != $password_hash) {
        return $response;
    } elseif ($getRow['verificated'] != 1) {
        return $response += 1;
    } else {
        $_SESSION['user_id'] = $getRow['id'];
    }
}