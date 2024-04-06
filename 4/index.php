<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';
  }
  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['gen'] = !empty($_COOKIE['gen_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['tel'] = !empty($_COOKIE['tel_error']);
  $errors['date'] = !empty($_COOKIE['date_error']);
  $errors['symbolfio_error'] = !empty($_COOKIE['symbolfio_error']);
  $errors['symboltel_error'] = !empty($_COOKIE['symboltel_error']);
  $errors['languages_error'] = !empty($_COOKIE['languages_error']);
  $errors['symbemail_error'] = !empty($_COOKIE['symbemail_error']);

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
  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['tel'] = empty($_COOKIE['tel_value']) ? '' : $_COOKIE['tel_value'];
  $values['gen'] = empty($_COOKIE['gen_value']) ? '' : $_COOKIE['gen_value'];
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
  $values['date'] = empty($_COOKIE['date_value']) ? '' : $_COOKIE['date_value'];
  $languages =isset($_COOKIE['languages']) ? unserialize($_COOKIE['languages']) : [];
  include('form.php');
} 
else  {
  $errors = FALSE;
  if (!preg_match("/^[а-я А-Я]+$/u", $_POST['fio'])) {
    setcookie('symbolfio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else if (empty($_POST['fio'])) {
    setcookie('fio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);
  }
  if (!preg_match('/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/', $_POST['tel'])) {
    setcookie('symboltel_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } 
   else if (empty($_POST['tel'])) {
    setcookie('tel_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    setcookie('tel_value', $_POST['tel'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['email'])) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else if (!preg_match("/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/", $_POST['email']) or (empty($_POST['email']))) {
    setcookie('symbemail_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['gen'])) {
    setcookie('gen_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
else{
    setcookie('gen_value', $_POST['gen'], time() + 365 * 24 * 60 * 60);
}
  if (empty($_POST['bio'])) {
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['date'])) {
    setcookie('date_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {

    setcookie('date_value', $_POST['date'], time() + 30 * 24 * 60 * 60);
  }

  if (empty($_POST['languages'])) {
    setcookie('languages_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } 
  else {
    $languages = $_POST['languages'];
    $languagesString = serialize($languages);

    // Устанавливаем cookie
    setcookie('languages', $languagesString, time() + 3600, '/'); // cookie будет храниться 1 час

  }

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
  }
  include 'p.php';

  $db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  foreach ($_POST['languages'] as $language) {
    $stmt = $db->prepare("SELECT id FROM languages WHERE id= :id");
    $stmt->bindParam(':id', $language);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
      print('Ошибка при добавлении языка.<br/>');
      exit();
    }
  }
  try {
    $stmt = $db->prepare("INSERT INTO application (names,tel,email,dateB,gender,biography)" . "VALUES (:fio,:tel,:email,:date,:gen,:bio)");
    $stmt->execute(array('fio' => $_POST['fio'], 'tel' => $_POST['tel'], 'email' => $_POST['email'], 'date' => $_POST['date'], 'gen' => $_POST['gen'], 'bio' => $_POST['fio']));
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

  setcookie('save', '1');
  header('Location: index.php');
}
