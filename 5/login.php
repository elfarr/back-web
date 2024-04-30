<!DOCTYPE html>

<?php

header('Content-Type: text/html; charset=UTF-8');
session_start();

// Проверяем, была ли уже начата сессия
if (!empty($_SESSION['login'])) {
    // Если есть логин в сессии, то пользователь уже авторизован.
    // TODO: Сделать выход (окончание сессии вызовом session_destroy()
    //при нажатии на кнопку Выход).
    session_destroy();
    // Делаем перенаправление на форму.
    header('Location: ./');
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
    //print_r($pas, true);
    if (!$pas) {
        exit("Логин не существует");
    }
    if ($pas["pass"] != md5($passLogin)) {
        exit("Неверный пароль");
    }
    $_SESSION['login'] =  $logLogin;
    $_SESSION['pass'] =  $passLogin;
    // Здесь необходимо получить id пользователя из базы данных и установить его в сессию
    $_SESSION['uid'] = $pas['id'];
    print( $_SESSION['login']);
    print( $_SESSION['pass']);
    print( $_SESSION['uid']);
    // После вывода сообщения выполним перенаправление
    header('Location: ./');
    exit();
}
?>
