<?php
header('Content-Type: text/html; charset=UTF-8');
$messages = array();
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
  if ($errors['gen']) {
    setcookie('gen_error', '', 100000);
    $messages[] = '<div class="error">Выберите пол.</div>';
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

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['tel'] = empty($_COOKIE['tel_value']) ? '' : $_COOKIE['tel_value'];
  $values['gen'] = empty($_COOKIE['gen_value']) ? '' : $_COOKIE['gen_value'];
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
  $values['date'] = empty($_COOKIE['date_value']) ? '' : $_COOKIE['date_value'];
  $languages = empty($_COOKIE['languages']) ? [] : unserialize($_COOKIE['languages']);
  include('form.php');
} else {
  // Проверяем ошибки.
  $errors = FALSE;
  if (!preg_match("/^[а-я А-Я]+$/u", $_POST['fio'])) {
    setcookie('symbolfio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  if (empty($_POST['fio'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('fio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);
  }
  if (!preg_match('/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/', $_POST['tel'])) {
    setcookie('symboltel_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else if (empty($_POST['tel'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('tel_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('tel_value', $_POST['tel'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['email'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else if (!preg_match("/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/", $_POST['email']) or (empty($_POST['email']))) {
    setcookie('symbemail_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['gen'])) {
    print('нет пола');
    setcookie('gen_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('gen_value', $_POST['gen'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['bio'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['date'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('date_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('date_value', $_POST['date'], time() + 30 * 24 * 60 * 60);
  }

  if (empty($_POST['languages'])) {
    setcookie('languages_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;

    foreach ($array as $key => $val) {
      if ($val == 0) {
        setcookie($key, '', 100000);
      }
    }
  } else {
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

  $user = 'u67314';
  $pass = '1682212';
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
    $stmt->execute(array('fio' => $fio, 'tel' => $tel, 'email' => $email, 'date' => $date, 'gen' => $gen, 'bio' => $bio));
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
