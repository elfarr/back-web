<!DOCTYPE html>

<?php


header('Content-Type: text/html; charset=UTF-8');

session_start(); // google
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

if (!empty($_SESSION['login'])) {
  
  header('Location: index.php');
  
}else{
?>
<html lang="ru">

<head>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <title>Задание 4</title>
</head>

<body>
<form action="login.php" method="post">
    <input name="login" />
    <input name="pass" />
    <input type="submit" value="Войти" />
</form>
<?php
}
}
else {
  $user = 'u47509';
  $pass = '2635406';
  $db = new PDO('mysql:host=localhost;dbname=u47509', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

  $passLogin = $_POST['pass'];
  $logLogin = $_POST['login'];

  $data = $db->prepare("SELECT pass FROM form where login = '$logLogin' ");
  $data->execute();
  $pas = $data->fetch(PDO::FETCH_ASSOC); // g

  if($pas['pass']!=$_POST['pass'] and $pas['login']!=$_POST['login'] and !empty( $pas['pass'])and !empty( $pas['login'])){     
    exit ("Логин или email не существует"); 
  }
  else{
      $_SESSION['login'] =  $logLogin;
      $_SESSION['pass'] =  $passLogin;
      $_SESSION['uid'] = $pas['id'];
      echo 'вы успешно вошли';
    header('Location: index.php');
  }
    
}
?>

  <header>
    <img id="logo" src="logo.jpg" alt="Наш лого" />
    <h1>Задание 5</h1>
  </header>

  <div class="form">
    <h2>Форма</h2>
    <form action="login.php" method="POST" accept-charset="UTF-8" class="login">
    <input name="login" />
    <input name="pass" />
    <input type="submit" value="Войти" />
    </form>
  </div>
</body>

</html>
</body>

</html>