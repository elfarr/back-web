<?php
session_start();
session_destroy();
header("Location: form.php"); // Перенаправление на страницу form.php после выхода
exit();
?>