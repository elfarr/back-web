<?php
// Подключение к базе данных
include '../4/p.php';
$db = new PDO('mysql:host=127.0.0.1;dbname=u67314', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Проверяем, передан ли параметр id через GET запрос
if(isset($_GET['id']) && !empty($_GET['id'])) {
    // Приведение id к целочисленному типу для безопасности
    $id = intval($_GET['id']);

    // Подготовленный запрос на удаление записи из application_language
    $sql_lang = "DELETE FROM application_language WHERE id_app = :id";
    $stmt_lang = $db->prepare($sql_lang);

    // Передача параметров и выполнение запроса на удаление связанных языков пользователя
    $stmt_lang->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_lang->execute();

    // Подготовленный запрос на удаление записи из application
    $sql_app = "DELETE FROM application WHERE id = :id";
    $stmt_app = $db->prepare($sql_app);

    // Передача параметров и выполнение запроса на удаление пользователя
    $stmt_app->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_app->execute();

    // Проверка на успешность выполнения запроса на удаление пользователя
    if($stmt_app->rowCount() > 0) {
        echo "Запись пользователя успешно удалена.";
        
    } else {
        echo "Не удалось удалить запись пользователя.";
    }
} else {
    echo "Не передан идентификатор записи.";
}
header('Location: admin.php');
?>
