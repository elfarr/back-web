<?php
function get_error() {
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
    return $errors;
}

function valid() {
    $errors = array();
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
      $db = connectToDatabase();
      if (empty($_POST['languages'])) {
        setcookie('languages_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
      } else {
        foreach ($_POST['languages'] as $language) {
          $stmt = $db->prepare("SELECT id FROM languages WHERE id= :id");
          $stmt->execute(array(':id' => $language));
          if ($stmt->rowCount() == 0) {
            setcookie('languages_unknown', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
          }
        }
        if (!empty($_POST['languages'])) {
            $languages = $_POST['languages'];
            $languagesString = serialize($languages);
            setcookie('languages', $languagesString, time() + 3600, '/'); // cookie будет храниться 1 час
          }
      }     
        
    return $errors;
}
function startSession($login, $pass, $uid) {
    if (!isset($_SESSION)) {
        session_start();
    }
    $_SESSION['login'] = $login;
    $_SESSION['pass'] = $pass;
    $_SESSION['uid'] = $uid;
}
function connectToDatabase() {
    include '../4/p.php';
    $db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

