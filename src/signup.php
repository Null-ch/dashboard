<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/db.php';
$message = '';

function signup($username, $email, $password)
{
    $response = 0;
    $password_hash = hash('sha256', $password);
    $query = pdo()->prepare("SELECT * FROM users WHERE email=:email");
    $query->bindParam("email", $email, PDO::PARAM_STR);
    $query->execute();
    if ($query->rowCount() > 0) {
        return $response;
    }
    if ($query->rowCount() == 0) {
        $query = pdo()->prepare("INSERT INTO users(login,password,email) VALUES (:username,:password_hash,:email)");
        $query->bindParam("username", $username, PDO::PARAM_STR);
        $query->bindParam("password_hash", $password_hash, PDO::PARAM_STR);
        $query->bindParam("email", $email, PDO::PARAM_STR);
        $result = $query->execute();
        if ($result) {
            return $response += 1;
        }
    }
}
?>