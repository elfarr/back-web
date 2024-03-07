<?php

header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  if (!empty($_GET['save'])) {

    print('Спасибо, результаты сохранены.');
  }

  include('form.php');

  exit();
}

$fio = $_POST['fio'];
$tel = $_POST['tel'];
$email = $_POST['email'];
$date = $_POST['date'];
$gen = $_POST['gen'];
$bio = $_POST['bio'];

$errors = FALSE;

if (empty($_POST['fio'])) {
  print('Заполните имя.<br/>');
  $errors = TRUE;
}
if (!preg_match('/^[a-zA-Zа-яА-Я\s]{1,150}$/u', $fio)) {
  print('ФИО некорректно.');
  $errors = TRUE;
}
$date_format = 'd.m.Y';
$date_timestamp = strtotime($_POST['date']);
$date_valid = date($date_format, $date_timestamp) === $_POST['date'];
if (empty($_POST['date']) || $date_valid){
  print('Дата некорректна<br/>');
  $errors = TRUE;
}

if (empty($_POST['tel'])) {
  print('Заполните телефон.<br/>');
  $errors = TRUE;
}
$tel_length = strlen($_POST['tel']);
if (!($tel_length == 11 || $tel_length == 12)) {
  print('Телефон некорректный.');
  $errors = TRUE;
}

if (empty($_POST['email'])) {
  print('Заполните почту.<br/>');
  $errors = TRUE;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  print('Почта некорректна.');
  $errors = TRUE;
}

if (empty($_POST['gen']) || ($_POST['gen']!="f" && $_POST['gen']!='m')) {
  print('Заполните пол.<br/>');
  print($_POST['gen']);
  $errors = TRUE;
}
if (empty($_POST['languages'])) {
  print('Выберите языки программирования.<br/>');
  $errors = TRUE;
}
if (empty($_POST['bio'])) {
  print('Заполните биографию.<br/>');
  $errors = TRUE;
}
if (!preg_match('/^[a-zA-Z0-9,.!? ]+$/', $bio)) {
  print('Биография содержит недопустимые символы.<br/>');
  $errors = TRUE;
}

if ($errors) {
  exit();
}

$user = 'u67314';
$pass = '1682212';
$db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
  $stmt = $db->prepare("INSERT INTO application (names,tel,email,dateB,gender,biography)" . "VALUES (:fio,:tel,:email,:date,:gen,:bio)");
  $stmt->execute(array('fio' => $fio, 'tel' => $tel, 'email' => $email, 'date' => $date, 'gen' => $gen, 'bio' => $bio));
  $applicationId = $db->lastInsertId();
 
  foreach ($_POST['languages'] as $language) {
    $stmt = $db->prepare("SELECT id FROM languages WHERE id= :id");
    $stmt->bindParam(':id', $language);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
     
      $stmt = $db->prepare("INSERT INTO application_language (id_app, id_lang) VALUES (:applicationId, :languageId)");
    $stmt->bindParam(':applicationId', $applicationId);
    $stmt->bindParam(':languageId', $language);
    $stmt->execute();

  } else {
    print('Ошибка при добавлении языка.<br/>');
  } 
};

  print('Спасибо, результаты сохранены.<br/>'); }

catch (PDOException $e) {
  echo $e->getMessage();
  exit();
}

header('Location: ?save=1');