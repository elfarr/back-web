<?php
session_start();
session_destroy();
setcookie('fio', '', 100000);
setcookie('bio', '', 100000);
setcookie('gen', '', 100000);
setcookie('date', '', 100000);
setcookie('email', '', 100000);
setcookie('tel', '', 100000);
setcookie('languages', '', 100000);
header("Location: index.php"); // Перенаправление на страницу form.php после выхода
exit();
?>