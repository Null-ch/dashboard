<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/core.php';

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $response = signup($username, $email, $password);
    if ($response === 0) {
        template('messages/alreadyRegistered.php', ["message" => 'Этот адрес уже зарегистрирован!']);
    } else {
        template('messages/success.php', ["message" => 'Регистрация прошла успешно! Авторизируйтесь']);
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $response = login($email, $password);

    if ($response === 0) {
        template('messages/alreadyRegistered.php', ["message" => 'Неверный логин или пароль']);
    } elseif ($response === 1) {
        template('messages/alreadyRegistered.php', ["message" => 'Ваша учетная запись еще не подтверждена']);
    } else {
        template('messages/success.php', ["message" => 'Вы авторизованны']);
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
    <link href="/assets/css/stylesLogin.css" rel="stylesheet" />
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
