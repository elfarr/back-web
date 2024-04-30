<?php
session_start();
session_destroy();
setcookie('fio_value', '', 100000);
setcookie('bio_value', '', 100000);
setcookie('gen_value', '', 100000);
setcookie('date_value', '', 100000);
setcookie('email_value', '', 100000);
setcookie('tel_value', '', 100000);
if (isset($_COOKIE['languages'])) {
    //unset($_COOKIE['languages']);
    setcookie('languages', '', time() - 3600, '/'); // Удаляем cookie, устанавливая время истечения в прошлое
}

header("Location: index.php"); // Перенаправление на страницу form.php после выхода
exit();
?>