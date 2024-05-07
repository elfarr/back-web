<head>
    <title>Задание 6</title>
</head>

<body>
    <?php
    include ('functions.php');


if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}

$db = connectToDatabase();
$username = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];

$stmt = $db->prepare("SELECT * FROM admin WHERE admin_login = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch();


if (!$admin || !password_verify($password, $admin['admin_pass'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}
?>


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
        <h1>Задание 6</h1>
    </header>
    <body>
        <div class="form" style="width: 70%";>
      <h2>Данные о пользователях</h2>
      <table border="1">
        <tr>
          <th>ID</th>
          <th>ФИО</th>
          <th>Телефон</th>
          <th>Email</th>
          <th>Дата рождения</th>
          <th>Пол</th>
          <th>Биография</th>
          <th>Языки</th>
          <th>Изменить</th>
    <th>Удалить</th>
        </tr>
        <?php
  
        $sql = "SELECT a.id, a.names, a.tel, a.email, a.dateB, a.gender, a.biography, GROUP_CONCAT(l.title) AS languages
                FROM application a
                LEFT JOIN application_language al ON a.id = al.id_app
                LEFT JOIN languages l ON al.id_lang = l.id GROUP BY a.id";
        $stmt = $db->query($sql);
    
     
        if ($stmt->rowCount() > 0) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["names"]."</td>";
                echo "<td>".$row["tel"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "<td>".$row["dateB"]."</td>";
                echo "<td>".$row["gender"]."</td>";
                echo "<td>".$row["biography"]."</td>";
                echo "<td>".$row["languages"]."</td>";
                echo "<td><a href='edit.php?id=".$row["id"]."'>Изменить</a></td>";
                echo "<td><a href='delete.php?id=".$row["id"]."'>Удалить</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No data available</td></tr>";
        }      ?>
  
      </table>   <?php
      $sql = "SELECT l.title AS language, COUNT(al.id_lang) AS count_users
            FROM languages l
            LEFT JOIN application_language al ON l.id = al.id_lang
            GROUP BY l.title";

    $stmt = $db->prepare($sql);
    $stmt->execute();


    echo "<h2>Статистика :</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Язык программирования</th><th>Количество пользователей</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>{$row['language']}</td><td>{$row['count_users']}</td></tr>";
    }
    echo "</table>";

?>

  </div>
</body>
</html>
