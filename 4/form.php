<!DOCTYPE html>

<html lang="ru">

<head>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <title>Задание 4</title>
</head>

<body>

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

  <header>
    <img id="logo" src="logo.jpg" alt="Наш лого" />
    <h1>Задание 4</h1>
  </header>
  <div class="form">
    <h2>Форма</h2>
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
        <input type="radio"  name="point1" <?php 
                if ($errors['point1']) {print 'class="error"' ;} 
                if( $values['point1'] == 'm') {print "checked='checked'";}?> value="m">
                    муж</label>
                <label>
                    <input type="radio" name="point1" <?php 
                if ($errors['point1']) {print 'class="error"' ;} 
                if( $values['point1'] == 'f') {print "checked='checked'";}?> value="f">
                    жен</label><br>
            </p>
        <label>
          Любимый язык программирования:
          <br>

          <select name="languages[]" multiple="multiple" <?php if ($errors['languages_error']) {
                                  print 'class="error"';
                                } ?>>
            <option value="1"<?php echo in_array('1', $languages) ? 'selected' : ''; ?>>Pascal</option>
            <option value="2" <?php echo in_array('2', $languages) ? 'selected' : ''; ?> >C</option>
            <option value="3" <?php echo in_array('3', $languages) ? 'selected' : ''; ?>>C++</option>
            <option value="4" <?php echo in_array('4', $languages) ? 'selected' : ''; ?>>JavaScript</option>
            <option value="5" <?php echo in_array('5', $languages) ? 'selected' : ''; ?>>PHP</option>
            <option value="6" <?php echo in_array('6', $languages) ? 'selected' : ''; ?>>Python</option>
            <option value="7" <?php echo in_array('7', $languages) ? 'selected' : ''; ?>>Java</option>
            <option value="8" <?php echo in_array('8', $languages) ? 'selected' : ''; ?>>Haskel</option>
            <option value="9" <?php echo in_array('9', $languages) ? 'selected' : ''; ?>>Clojure</option>
            <option value="10" <?php echo in_array('10', $languages) ? 'selected' : ''; ?>>Prolog</option>
            <option value="11" <?php echo in_array('11', $languages) ? 'selected' : ''; ?>>Scala</option>
            <option value="12" <?php echo in_array('12', $languages) ? 'selected' : ''; ?>>Несуществующий для теста</option>
          </select> </label><br />

        <label>
          Биография:<br />
          <textarea name="bio" <?php if ($errors['bio']) {
                                  print 'class="error"';
                                } ?>> <?php print $values['bio']; ?> </textarea></label><br />

        <label><input type="checkbox" name="check" required /> С контрактом
          ознакомлен</label><br />

        <input type="submit" value="Сохранить" />
    </form>
  </div>
</body>

</html>
</body>

</html>