<?php
session_start();
session_destroy();
header("Location: index.php"); // Перенаправление на страницу form.php после выхода
exit();
?>