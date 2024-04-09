<?php
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
$session_started = false;
if ($_COOKIE[session_name()] && session_start()) {
  $session_started = true;
  if (!empty($_SESSION['login'])) {
    // Если есть логин в сессии, то пользователь уже авторизован.
    // TODO: Сделать выход (окончание сессии вызовом session_destroy()
    //при нажатии на кнопку Выход).
    // Делаем перенаправление на форму.
    header('Location: ./');
    exit();
  }
}

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<form action="login.php" method="post">
  <input name="login" />
  <input name="pass" />
  <input type="submit" value="Войти" />
</form>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  include 'p.php';
    $db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // TODO: Проверть есть ли такой логин и пароль в базе данных.
  $passLogin = $_POST['pass'];
  $logLogin = $_POST['login'];
  $data = $db->prepare("SELECT pass FROM user where login = '$logLogin' ");
  $data->execute();
  $pas = $data->fetch(PDO::FETCH_ASSOC); 
  if($pas['pass']!=$_POST['pass'] and $pas['login']!=$_POST['login'] and !empty( $pas['pass'])and !empty( $pas['login'])){     
    exit ("Логин или email не существует"); 
  }
  // Выдать сообщение об ошибках.

  if (!$session_started) {
    session_start();
  }
  // Если все ок, то авторизуем пользователя.
  // Записываем ID пользователя.
  else{
    $_SESSION['login'] =  $logLogin;
    $_SESSION['pass'] =  $passLogin;
    $_SESSION['uid'] = $pas['id'];
    echo 'вы успешно вошли';
  header('Location: index.php');
}
}
