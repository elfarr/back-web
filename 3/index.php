<?php

header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty($_GET['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.
    print('Спасибо, результаты сохранены.');
  }
  // Включаем содержимое файла form.php.
  include('form.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
$fio = $_POST['fio'];
$tel = $_POST['tel'];
$email = $_POST['email'];
$date = $_POST['date'];
$gen = $_POST['gen'];
$bio = $_POST['bio'];
// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['fio'])) {
  print('Заполните имя.<br/>');
  $errors = TRUE;
}

if (empty($_POST['date'])) {
  print('Заполните дату.<br/>');
  $errors = TRUE;
}

if (empty($_POST['tel'])) {
  print('Заполните телефон.<br/>');
  $errors = TRUE;
}

if (empty($_POST['email'])) {
  print('Заполните почту.<br/>');
  $errors = TRUE;
}

if (empty($_POST['gen'])) {
  print('Заполните пол.<br/>');
  $errors = TRUE;
}

if (empty($_POST['bio'])) {
  print('Заполните биографию.<br/>');
  $errors = TRUE;
}

if ($errors) {
  // При наличии ошибок завершаем работу скрипта.
  exit();
}

$user = 'u67314'; // Заменить на ваш логин uXXXXX
$pass = '1682212'; // Заменить на пароль, такой же, как от SSH
$db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
  $stmt = $db->prepare("INSERT INTO application (names,tel,email,dateB,gender,biography)"."VALUES (:fio,:tel,:email,:date,:gen,:bio)");
  $stmt -> execute(array('fio'=>$fio,'tel'=>$tel,'email'=>$email,'date'=>$date,'gen'=>$gen,'bio'=>$bio));
  $applicationId = $db->lastInsertId();
foreach ($_POST['languages'] as $language) {
    $stmt = $pdo->prepare("INSERT INTO application_languages (id_app, id_lang) VALUES (:applicationId, :languageId)");
    $stmt->bindParam(':applicationId', $applicationId);
    $stmt->bindParam(':languageId', $language);
    $stmt->execute();
}
  // $stmt = $db->prepare("INSERT INTO application_language (pascal,c,c_plus_plus,js,php,python,java,haskel,clojure,prolog,scala)"." VALUES (:Pascal,:C,:C_plus_plus,:JavaScript,:PHP,:Python,:Java,:Haskel,:Clojure,:Prolog,:Scala)");
  // $stmt -> execute(array('Pascal'=>$Pascal, 'C'=>$C, 'C_plus_plus'=>$C_plus_plus, 'JavaScript'=>$JavaScript, 'PHP'=>$PHP, 'Python'=>$Python,'Java'=>$Java, 'Haskel'=>$Haskel, 'Clojure'=>$Clojure, 'Prolog'=>$Prolog, 'Scala'=>$Scala));
  print ('Спасибо, результаты сохранены.<br/>');
}
catch(PDOException $e){
  echo $e->getMessage();
  exit();
}


//  stmt - это "дескриптор состояния".
 
//  Именованные метки.
//$stmt = $db->prepare("INSERT INTO test (label,color) VALUES (:label,:color)");
//$stmt -> execute(['label'=>'perfect', 'color'=>'green']);
 
//Еще вариант
/*$stmt = $db->prepare("INSERT INTO users (firstname, lastname, email) VALUES (:firstname, :lastname, :email)");
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':email', $email);
$firstname = "John";
$lastname = "Smith";
$email = "john@test.com";
$stmt->execute();
*/

// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
header('Location: ?save=1');
