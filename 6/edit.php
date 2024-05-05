<?php
 include '../4/p.php';
 $db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
 $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
   
try {
    

    // Проверяем, был ли передан параметр id через GET запрос
    if(isset($_GET['id']) && !empty($_GET['id'])) {
        $id = intval($_GET['id']);

        // Получаем данные пользователя из таблицы application
        $stmt = $db->prepare("SELECT names, tel, email, dateB, gender, biography FROM application WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Получаем языки пользователя из таблицы application_language
        $stmt_lang = $db->prepare("SELECT id_lang FROM application_language WHERE id_app = ?");
        $stmt_lang->execute([$id]);
        $languages = array();
        while ($row_lang = $stmt_lang->fetch(PDO::FETCH_ASSOC)) {
            $languages[] = $row_lang['id_lang'];
        }

        // Заполняем значения в форме редактирования
        $values['fio'] =  strip_tags($row['names']);
        $values['email'] =  strip_tags($row['email']);
        $values['tel'] =  strip_tags($row['tel']);
        $values['gen'] = $row['gender'];
        $values['bio'] = $row['biography'];
        $values['date'] = $row['dateB'];
    } else {
        echo "Не передан идентификатор записи.";
        exit();
    }
} catch (PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
    exit();
}
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
<form action="edit.php" method="POST" accept-charset="UTF-8" class="login">

<h2>Изменение данных пользователя</h2>
<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
      <label>
        ФИО:<br> <input name="fio" value="<?php print $values['fio']; ?>"> </label><br>
      <label>
        Номер телефона :<br />
        <input name="tel" value="<?php print $values['tel']; ?>"> </label><br>
      <label>
        Email:<br />
        <input name="email" value="<?php print $values['email']; ?>" type="email">
      </label><br>

      <label>
        Дата рождения:<br />
        <input name="date" value="<?php print $values['date']; ?>" type="date">
        <br>
        <br />
        <label>Пол:<br />
        <input type="radio"  name="gen" <?php 
                if( $values['gen'] == 'm') {print "checked='checked'";}?> value="m">
                    муж</label>
                <label>
                    <input type="radio" name="gen" <?php 
          
                if( $values['gen'] == 'f') {print "checked='checked'";}?> value="f">
                    жен</label><br>
            </p>
        <label>
          Любимый язык программирования:
          <br>

          <select name="languages[]" multiple="multiple">
            <option value="1"<?php echo is_array($languages) &&  in_array('1', $languages) ? 'selected' : ''; ?>>Pascal</option>
            <option value="2" <?php echo is_array($languages) && in_array('2', $languages) ? 'selected' : ''; ?> >C</option>
            <option value="3" <?php echo is_array($languages) && in_array('3', $languages) ? 'selected' : ''; ?>>C++</option>
            <option value="4" <?php echo is_array($languages) && in_array('4', $languages) ? 'selected' : ''; ?>>JavaScript</option>
            <option value="5" <?php echo is_array($languages) && in_array('5', $languages) ? 'selected' : ''; ?>>PHP</option>
            <option value="6" <?php echo  is_array($languages) &&in_array('6', $languages) ? 'selected' : ''; ?>>Python</option>
            <option value="7" <?php echo is_array($languages) && in_array('7', $languages) ? 'selected' : ''; ?>>Java</option>
            <option value="8" <?php echo is_array($languages) && in_array('8', $languages) ? 'selected' : ''; ?>>Haskel</option>
            <option value="9" <?php echo is_array($languages) && in_array('9', $languages) ? 'selected' : ''; ?>>Clojure</option>
            <option value="10" <?php echo is_array($languages) && in_array('10', $languages) ? 'selected' : ''; ?>>Prolog</option>
            <option value="11" <?php echo is_array($languages) && in_array('11', $languages) ? 'selected' : ''; ?>>Scala</option>
            <option value="12" <?php echo is_array($languages) && in_array('12', $languages) ? 'selected' : ''; ?>>Несуществующий для теста</option>
          </select> </label><br />

        <label>
          Биография:<br />
          <textarea name="bio" ><?php print $values['bio']; ?></textarea></label><br />

        <input type="submit" value="Изменить данные" />
    </form>
    </div>
</body>
</html>

<?php
}
else {
try {
    $stmt = $db->prepare("SELECT id FROM application WHERE id = ?");
    $stmt->execute([$_POST['id']]);

    $row = $stmt->fetch();
      if ($row) {
        $applicationId = $_POST['id'];
        // Удалить текущие языки программирования для данной заявки
        $deleteStmt = $db->prepare("DELETE FROM application_language WHERE id_app = :applicationId");
        $deleteStmt->bindParam(':applicationId', $applicationId);
        $deleteStmt->execute();
        // Затем вставить новые языки программирования
        $languages = $_POST['languages'];
        foreach ($languages as $languageId) {
            $insertStmt = $db->prepare("INSERT INTO application_language (id_app, id_lang) VALUES (:applicationId, :languageId)");
            $insertStmt->bindParam(':applicationId', $applicationId);
            $insertStmt->bindParam(':languageId', $languageId);
            $insertStmt->execute();
        }
    }
    
      
      $stmt = $db->prepare("UPDATE application SET names = :fio, tel = :tel, email = :email, dateB = :date, gender = :gen, biography = :bio  WHERE id = $applicationId");

  
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
    header('Location: admin.php');}