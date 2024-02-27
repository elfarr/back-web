<!DOCTYPE html>

<html lang="ru">

<head>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <title>Задание 3</title>
</head>

<body>
  <header>
    <img id="logo" src="logo.jpg" alt="Наш лого" />
    <h1>Задание 3</h1>
  </header>
  <div class="form">
    <h2>Форма</h2>
    <form action="index.php" method="POST" accept-charset="UTF-8" class="login">
      <label>
        ФИО:<br> <input name="fio"> </label><br>
      <label>
        Номер телефона :<br />
        <input name="tel" type="tel"> </label><br>
      <label>
        Email:<br />
        <input name="email" type="email"> </label><br>

      <label>
        Дата рождения:<br />
        <input name="date" type="date"> </label>
      <br>

        <label>Пол:<br /><input type="radio" value="m" name="gen" />
          муж</label>
        <label><input type="radio" value="f" name="gen" /> жен</label><br>

        <label>
          Любимый язык программирования:
          <br>
          <select name="languages[]" multiple="multiple">
            <option value="Pascal">Pascal</option>
            <option value="C">C</option>
            <option value="C_plus_plus">C++</option>
            <option value="JavaScript">JavaScript</option>
            <option value="PHP">PHP</option>
            <option value="Python">Python</option>
            <option value="Java">Java</option>
            <option value="Haskel">Haskel</option>
            <option value="Clojure">Clojure</option>
            <option value="Prolog">Prolog</option>
            <option value="Scala">Scala</option>
          </select> </label><br />

        <label>
          Биография:<br />
          <textarea name="bio"></textarea></label><br />

        <label><input type="checkbox" name="check" /> С контрактом
          ознакомлен</label><br />

        <input type="submit" value="Сохранить" />
    </form>
  </div>
</body>

</html>
