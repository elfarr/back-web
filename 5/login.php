<!DOCTYPE html>

<?php

header('Content-Type: text/html; charset=UTF-8');
$session_started = false;

// Проверяем, была ли уже начата сессия
if (!empty($_COOKIE[session_name()]) && session_status() === PHP_SESSION_ACTIVE) {
    $session_started = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<html lang="ru">

<head>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>Задание 5</title>
</head>

<body>
    <header>
        <img id="logo" src="logo.jpg" alt="Наш лого" />
        <h1>Задание 5</h1>
    </header>

    <div class="form">
        <h2>Форма входа</h2>
        <form action="" method="POST" accept-charset="UTF-8" class="login">
            <input name="login" />
            <input name="pass" />
            <input type="submit" value="Войти" />
        </form>
    </div>
</body>

</html>

<?php
} else {
    include '../4/p.php';
    $db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $passLogin = $_POST['pass'];
    $logLogin = $_POST['login'];

    $data = $db->prepare("SELECT id, pass FROM application where login = ?");

    $data->execute([$logLogin]);
    $pas = $data->fetch(PDO::FETCH_ASSOC);
    print_r($pas, true);
    if (!$pas) {
        exit("Логин или email не существует");
    }
    
    if (!$session_started) {
        session_start();
    }

    $_SESSION['login'] =  $logLogin;
    $_SESSION['pass'] =  $passLogin;
    // Здесь необходимо получить id пользователя из базы данных и установить его в сессию
    $_SESSION['uid'] = $pas['id'];
    echo 'Вы успешно вошли';
    // После вывода сообщения выполним перенаправление
    header('Location: ./');
    exit();
}
?>
