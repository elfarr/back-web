<?php
header('Content-Type: text/html; charset=UTF-8');

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
  $errors['languages_unknown'] = !empty($_COOKIE['languages_unknown']);
  $errors['date_value_error'] = !empty($_COOKIE['date_value_error']);
  $errors['bio_value_error'] = !empty($_COOKIE['bio_value_error']);

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


  if (
    empty($errors) && !empty($_COOKIE[session_name()]) &&
    session_start() && !empty($_SESSION['login'])
  ) {
    try {
      include '../4/p.php';
      $db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $log = $_SESSION['login'];
      $passForm = $_SESSION['pass'];

      $stmt = $db->prepare("SELECT names,tel,email,dateB,gender,biography FROM application WHERE id = ?");
      $stmt->execute([$_SESSION['uid']]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      foreach ($stmt as $row) {
        $values['fio'] = $row['names'];
        $values['email'] = $row['email'];
        $values['tel'] = $row['dateB'];
        $values['gen'] = $row['gender'];
        $values['bio'] = $row['limbs'];
        $values['date'] = $row['biography'];
      }


      $stmt1  = $db->prepare("SELECT id_lang FROM application_languages where login = ? AND pass = ?");
      $stmt1->bindParam($_SESSION['login'], $_SESSION['pass']);
      $stmt1->execute();
      $languages = array();
      while ($stmt1->fetch()) {
        $languages[] = $id_lang;
      }
    } catch (PDOException $e) {
      echo 'Ошибка: ' . $e->getMessage();
      exit();
    }
    printf('Вход с логином %s,', $_SESSION['login']);
  }
  include('form.php');
} else {

  $errors = FALSE;
  if (!preg_match("/^[а-я А-Я]+$/u", $_POST['fio'])) {
    setcookie('symbolfio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else if (empty($_POST['fio'])) {
    setcookie('fio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);

  if (!preg_match('/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/', $_POST['tel'])) {
    setcookie('symboltel_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else if (empty($_POST['tel'])) {
    setcookie('tel_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('tel_value', $_POST['tel'], time() + 30 * 24 * 60 * 60);

  if (empty($_POST['email'])) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else if (!preg_match("/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/", $_POST['email']) or (empty($_POST['email']))) {
    setcookie('symbemail_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  if (empty($_POST['gen']) || ($_POST['gen'] != "f" && $_POST['gen'] != 'm')) {
    setcookie('gen_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('gen_value', $_POST['gen'], time() + 365 * 24 * 60 * 60);

  if (empty($_POST['bio'])) {
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
  } else if (!preg_match('/^[a-zA-Zа-яА-Я0-9,.!? ]+$/', $_POST['bio'])) {
    setcookie('bio_value_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
  $date_format = 'd.m.Y';
  $date_timestamp = strtotime($_POST['date']);
  $date_valid = date($date_format, $date_timestamp) === $_POST['date'];
  if (empty($_POST['date'])) {
    setcookie('date_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else if ($date_valid) {
    setcookie('date_value_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('date_value', $_POST['date'], time() + 30 * 24 * 60 * 60);
  include '../4/p.php';

  $db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if (empty($_POST['languages'])) {
    setcookie('languages_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    foreach ($_POST['languages'] as $language) {
      $stmt = $db->prepare("SELECT id FROM languages WHERE id= :id");
      $stmt->bindParam(':id', $language);
      $stmt->execute();
      if ($stmt->rowCount() == 0) {
        setcookie('languages_unknown', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
      }
    }
  } {
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
  if (
    !empty($_COOKIE[session_name()]) &&
    session_start() && !empty($_SESSION['login'])
  ) {
    include '../4/p.php';

    $db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $logForm = $_SESSION['login'];
    $passForm = $_SESSION['pass'];
    $user_id = $db->lastInsertId();
    try {
      $stmt = $db->prepare("UPDATE application SET names = :fio, tel = :tel, email = :email, dateB = :date, gender = :gen, biography = :bio");
      $stmt->execute(array('fio' => $_POST['fio'], 'tel' => $_POST['tel'], 'email' => $_POST['email'], 'date' => $_POST['date'], 'gen' => $_POST['gen'], 'bio' => $_POST['bio']));
      $applicationId = $db->lastInsertId();

      foreach ($_POST['languages'] as $language) {
        $stmt = $db->prepare("UPDATE application_language SET id_lang = :languageId WHERE id_app = :applicationId");
        $stmt->bindParam(':applicationId', $applicationId);
        $stmt->bindParam(':languageId', $language);
        $stmt->execute();
    }
    

      print('Спасибо, результаты сохранены.<br/>');
    } catch (PDOException $e) {
      echo $e->getMessage();
      exit();
    }
  } else {
    $login = uniqid();
    $paddHash = rand(1, 3); // google
    $passX = substr(md5($paddHash), 0, 8);
    setcookie('login', $login);
    setcookie('pass', $passX);
    include '../4/p.php';
    $db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
      $stmt = $db->prepare("INSERT INTO application (names,tel,email,dateB,gender,biography,hash,login,pass)" . "VALUES (:fio,:tel,:email,:date,:gen,:bio,:hash,:login,:pass)");
      $stmt->execute(array(
        'fio' => $_POST['fio'], 'tel' => $_POST['tel'], 'email' => $_POST['email'], 'date' => $_POST['date'], 'gen' => $_POST['gen'], 'bio' => $_POST['bio'], 'hash' => $paddHash,
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
