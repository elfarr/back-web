<!DOCTYPE html>

<html lang="ru">

<head>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <title>Задание 3</title>
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
    <h1>Задание 3</h1>
  </header>
  <div class="form">
    <h2>Форма</h2>
    <form action="index.php" method="POST" accept-charset="UTF-8" class="login">
      <label>
        ФИО:<br> <input name="fio" <?php if ($errors['fio']) {
                                      print 'class="error"';
                                    } ?> value="<?php print $values['fio']; ?>"> </label><br>
      <label>
        Номер телефона :<br />
        <input name="tel" <?php if ($errors['tel']) {
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

        <label>Пол:<br />
          <input name="gen" <?php if ($errors['gen']) {
                              print 'class="error"';
                            } ?> value="<?php print $values['gen']; ?>" type="radio" value="m">
          муж</label>
        <label> <input name="gen" <?php if ($errors['gen']) {
                                    print 'class="error"';
                                  } ?> value="<?php print $values['gen']; ?>" type="radio" value="f"> жен</label><br>

        <label>
          Любимый язык программирования:
          <br>

          <select name="languages[]" multiple="multiple">
            <option value="1">Pascal</option>
            <option value="2" >C</option>
            <option value="3">C++</option>
            <option value="4">JavaScript</option>
            <option value="5">PHP</option>
            <option value="6">Python</option>
            <option value="7">Java</option>
            <option value="8">Haskel</option>
            <option value="9" >Clojure</option>
            <option value="10">Prolog</option>
            <option value="11" >Scala</option>
            <option value="12">Несуществующий для теста</option>
          </select> </label><br />

        <label>
          Биография:<br />
          <textarea name="bio" <?php if ($errors['bio']) {
                                  print 'class="error"';
                                } ?> value="<?php print $values['bio']; ?>"></textarea></label><br />

        <label><input type="checkbox" name="check" required /> С контрактом
          ознакомлен</label><br />

        <input type="submit" value="Сохранить" />
    </form>
  </div>
</body>

</html>
</body>

</html>