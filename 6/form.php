<!DOCTYPE html>

<html lang="ru">

<head>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <title>Задание 5</title>
</head>

<body>


  <header>
    <img id="logo" src="logo.jpg" alt="Наш лого" />
    <h1>Задание 5</h1>
  </header>
  <?php
  if (!empty($messages)) {
    print('<div id="messages">');
    // Выводим все сообщения.
    foreach ($messages as $message) {
      print($message);
    }
    print('</div>');
  }
?>
<div class="container">
        <?php
            //session_start();
            if(isset($_SESSION['login'])) {
                echo '<a href="logout.php" class="btn btn-danger type="reset"">Выйти</a>';
            } else {
                echo '<a href="login.php" class="btn btn-outline-info">Войти</a>';
            }
        ?>
    </div>
  <div class="form">
    <h2>Форма регистрации</h2>
    
    <form action="index.php" method="POST" accept-charset="UTF-8" class="login">
      <label>
        ФИО:<br> <input name="fio" <?php if ($errors['fio'] || $errors['symbolfio_error']) {
                                      print 'class="error"';
                                    } ?> value="<?php print $values['fio']; ?>"> </label><br>
      <label>
        Номер телефона :<br />
        <input name="tel" <?php if ($errors['tel'] || $errors['symboltel_error']) {
                            print 'class="error"';
                          } ?> value="<?php print $values['tel']; ?>"> </label><br>
      <label>
        Email:<br />
        <input name="email" <?php if ($errors['email']) {
                              print 'class="error"';  
                            } ?> value="<?php print $values['email']; ?>" type="email">
      </label><br>

      <label>
        Дата рождения:<br />
        <input name="date" <?php if ($errors['date']) {
                              print 'class="error"';
                            } ?> value="<?php print $values['date']; ?>" type="date">
        <br>
        <br />
        <label>Пол:<br />
        <input type="radio"  name="gen" <?php 
                if ($errors['gen']) {print 'class="error"' ;} 
                if( $values['gen'] == 'm') {print "checked='checked'";}?> value="m">
                    муж</label>
                <label>
                    <input type="radio" name="gen" <?php 
                if ($errors['gen']) {print 'class="error"' ;} 
                if( $values['gen'] == 'f') {print "checked='checked'";}?> value="f">
                    жен</label><br>
            </p>
        <label>
          Любимый язык программирования:
          <br>

          <select name="languages[]" multiple="multiple" <?php if ($errors['languages_error']) {
                                  print 'class="error"';
                                } ?>>
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
          <textarea name="bio" <?php if ($errors['bio']) {
                                  print 'class="error"';
                                } ?>><?php print $values['bio']; ?></textarea></label><br />

        <label><input type="checkbox" name="check" required /> С контрактом
          ознакомлен</label><br />

        <input type="submit" value="Сохранить" />
    </form>
  </div>
</body>

</html>
</body>

</html>