<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/core.php';
$_SESSION['user_id'] = null;
session_destroy();
header('Location: /login');