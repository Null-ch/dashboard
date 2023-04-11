<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/core.php';
require $_SERVER['DOCUMENT_ROOT'] . '/templates/styles.php';

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $response = signup($username, $email, $password);
    if ($response === 0) {
        $message = 'Этот адрес уже зарегистрирован!';
        template('messages/alreadyRegistered.php', ["message" => "$message"]);
    } else {
        $message = 'Регистрация прошла успешно! Авторизируйтесь';
        template('messages/success.php', ["message" => "$message"]);
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $response = login($email, $password);

    if ($response === 0) {
        $message = 'Неверный логин или пароль';
        template('messages/alreadyRegistered.php', ["message" => "$message"]);
    } else {
            $message = 'Вы авторизованны';
            template('messages/success.php', ["message" => "$message"]);
            echo "<script> location.href='/'; </script>";
            exit;
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Авторизация</title>
    <link rel="stylesheet" type="text/css" href="slide navbar style.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>


<body>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">
        <div class="signup">
            <form method="POST">
                <label for="chk" aria-hidden="true">Регистрация</label>
                <input type="username" name="username" placeholder="Логин" required="">
                <input type="email" name="email" placeholder="Email" required="">
                <input type="password" name="password" placeholder="Пароль" required="">
                <button name="signup" type="submit">Зарегистрироваться</button>
            </form>

        </div>
        <div class="login">
            <form method="POST">
                <label for="chk" aria-hidden="true">Авторизация</label>
                <input type="email" name="email" placeholder="Email" required="">
                <input type="password" name="password" placeholder="Пароль" required="">
                <button name="login" type="submit">Авторизироваться</button>
            </form>
        </div>
    </div>
</body>

</html>
