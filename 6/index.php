<?php
header('Content-Type: text/html; charset=UTF-8');
$action = "index.php";
include ('functions.php');
         
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены. ';
    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf(
        '<a href="login.php">Войдите</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass'])
      );
    }
  }

  $errors = get_error();

  if ($errors['fio']) {
    setcookie('fio_error', '', 100000);
    $messages[] = '<div class="error">Заполните имя.</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Заполните почту.</div>';
  }

  if ($errors['bio']) {
    setcookie('bio_error', '', 100000);
    $messages[] = '<div class="error">Заполните биографию.</div>';
  }
  if ($errors['tel']) {
    setcookie('tel_error', '', 100000);
    $messages[] = '<div class="error">Заполните номер телефона.</div>';
  }
  if ($errors['date']) {
    setcookie('date_error', '', 100000);
    $messages[] = '<div class="error">Заполните дату.</div>';
  }
  if ($errors['gen']) {
    setcookie('gen_error', '', 100000);
    $messages[] = '<div class="error">Введите пол</div>';
  }
  if ($errors['symbolfio_error']) {
    setcookie('symbolfio_error', '', 100000);
    $messages[] = '<div class="error">ФИО содержит недопустимые символы.</div>';
  }

  if ($errors['symboltel_error']) {
    setcookie('symboltel_error', '', 100000);
    $messages[] = '<div class="error">Укажите номер телефона в формате +7 (XXX) XXX-XX-XX.</div>';
  }
  if ($errors['languages_error']) {
    setcookie('languages_error', '', 100000);
    $messages[] = '<div class="error">Выберите языки.</div>';
  }
  if ($errors['languages_unknown']) {
    setcookie('languages_unknown', '', 100000);
    $messages[] = '<div class="error">Ошибка при добавлении языка.</div>';
  }
  if ($errors['date_value_error']) {
    setcookie('date_value_error', '', 100000);
    $messages[] = '<div class="error">Заполните дату в формате d.m.Y.</div>';
  }
  if ($errors['bio_value_error']) {
    setcookie('bio_value_error', '', 100000);
    $messages[] = '<div class="error">Биография содержит недопустимые символы.</div>';
  }

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['tel'] = empty($_COOKIE['tel_value']) ? '' : $_COOKIE['tel_value'];
  $values['gen'] = empty($_COOKIE['gen_value']) ? '' : $_COOKIE['gen_value'];
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
  $values['date'] = empty($_COOKIE['date_value']) ? '' : $_COOKIE['date_value'];
  $languages = isset($_COOKIE['languages']) ? unserialize($_COOKIE['languages']) : [];


  session_start();
  if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login'])) {
    try {
      $db = connectToDatabase();
      $log = $_SESSION['login'];
      $passForm = $_SESSION['pass'];

      $stmt = $db->prepare("SELECT names, tel, email, dateB, gender, biography FROM application WHERE id = ?");
      $stmt->execute([$_SESSION['uid']]);
      
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $values['fio'] =  strip_tags($row['names']);
        $values['email'] =  strip_tags($row['email']);
        $values['tel'] =  strip_tags($row['tel']);
        $values['gen'] = $row['gender'];
        $values['bio'] = $row['biography'];
        $values['date'] = $row['dateB'];
        $stmt1 = $db->prepare("SELECT id_lang FROM application_language WHERE id_app = ?");
        $stmt1->execute([$_SESSION['uid']]);
  
        $languages = array();
        while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
            $languages[] = $row['id_lang'];
        }

        
        
    } catch (PDOException $e) {
      echo 'Ошибка: ' . $e->getMessage();
      exit();
    }
    printf('Вход с логином %s', $_SESSION['login']);
  }
  include('header.php');
  include('form.php');
} 
else {

  $errors = valid();
 

  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  } else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('fio_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('gen_error', '', 100000);
    setcookie('date_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('tel_error', '', 100000);
    setcookie('symbolfio_error', '', 100000);
    setcookie('languages_error', '', 100000);
    setcookie('symbemail_error', '', 100000);
    setcookie('languages_unknown', '', 100000);
    setcookie('bio_value_error', '', 100000);
    setcookie('date_value_error', '', 100000);
  }
  if (!empty($_COOKIE[session_name()]) &&
    session_start() && !empty($_SESSION['login'])
  ) {
    $db = connectToDatabase();
    $logForm = $_SESSION['login'];
    $passForm = $_SESSION['pass'];
    $user_id = $db->lastInsertId();
    try {
      $stmt = $db->prepare("SELECT id FROM application WHERE id = ?");
      $stmt->execute([$_SESSION['uid']]);
  
      $row = $stmt->fetch();
      if ($row) {
        $applicationId = $row['id'];
        // Удалить текущие языки программирования для данной заявки
        $deleteStmt = $db->prepare("DELETE FROM application_language WHERE id_app = :applicationId");
        $deleteStmt->bindParam(':applicationId', $applicationId);
        $deleteStmt->execute();
        $languages = $_POST['languages'];
        foreach ($languages as $languageId) {
            $insertStmt = $db->prepare("INSERT INTO application_language (id_app, id_lang) VALUES (:applicationId, :languageId)");
            $insertStmt->bindParam(':applicationId', $applicationId);
            $insertStmt->bindParam(':languageId', $languageId);
            $insertStmt->execute();
        }
    }
    
      
      $stmt = $db->prepare("UPDATE application SET names = :fio, tel = :tel, email = :email, dateB = :date, gender = :gen, biography = :bio  WHERE id = :id");

      $stmt->bindParam(':id', $_SESSION['id']);
      $stmt->bindParam(':fio', $_POST['fio']);
      $stmt->bindParam(':tel', $_POST['tel']);
      $stmt->bindParam(':email', $_POST['email']);
      $stmt->bindParam(':date', $_POST['date']);
      $stmt->bindParam(':gen', $_POST['gen']);
      $stmt->bindParam(':bio', $_POST['bio']);
      $stmt->execute();
      
    
    } catch (PDOException $e) {
      echo $e->getMessage();
      exit();
    }
  } else {
    $login = uniqid();
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $pass = '';
    $length = 10;
    for ($i = 0; $i < $length; $i++) {
        $pass .= $characters[rand(0, $charactersLength - 1)];
    }
    $passX = md5($pass);
    setcookie('login', $login);
    setcookie('pass', $pass);
    $db = connectToDatabase();
    try {
      $stmt = $db->prepare("INSERT INTO application (names,tel,email,dateB,gender,biography,login,pass)" . "VALUES (:fio,:tel,:email,:date,:gen,:bio,:login,:pass)");
      $stmt->execute(array(
        'fio' => $_POST['fio'], 'tel' => $_POST['tel'], 'email' => $_POST['email'], 'date' => $_POST['date'], 'gen' => $_POST['gen'], 'bio' => $_POST['bio'],
        'login' => $login, 'pass' => $passX
      ));
      $applicationId = $db->lastInsertId();

      foreach ($_POST['languages'] as $language) {
        $stmt = $db->prepare("INSERT INTO application_language (id_app, id_lang) VALUES (:applicationId, :languageId)");
        $stmt->bindParam(':applicationId', $applicationId);
        $stmt->bindParam(':languageId', $language);
        $stmt->execute();
      };

      print('Спасибо, результаты сохранены.<br/>');
    } catch (PDOException $e) {
      echo $e->getMessage();
      exit();
    }
  }
  setcookie('save', '1');
  header('Location: index.php');
}