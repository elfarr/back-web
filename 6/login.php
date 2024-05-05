<?php
function connectToDatabase() {
    include '../4/p.php';
    $db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

function startSession($login, $pass, $uid) {
    if (!isset($_SESSION)) {
        session_start();
    }
    $_SESSION['login'] = $login;
    $_SESSION['pass'] = $pass;
    $_SESSION['uid'] = $uid;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Отображение формы входа
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <title>Задание 6</title>
    </head>
    <body>
        <header>
            <img id="logo" src="logo.jpg" alt="Наш лого" />
            <h1>Задание 6</h1>
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
    // Обработка входа
    $db = connectToDatabase();
    $logLogin = $_POST['login'];
    $passLogin = $_POST['pass'];

    $stmt = $db->prepare("SELECT id, pass FROM application WHERE login = ?");
    $stmt->execute([$logLogin]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        exit("Логин или email не существует");
    }

    startSession($logLogin, $passLogin, $user['id']);
    header('Location: ./');
    exit();
}
?>
