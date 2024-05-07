<?php
 include ('functions.php');
$db = connectToDatabase();


if(isset($_GET['id']) && !empty($_GET['id'])) {
 
    $id = intval($_GET['id']);

   
    $sql_lang = "DELETE FROM application_language WHERE id_app = :id";
    $stmt_lang = $db->prepare($sql_lang);

  
    $stmt_lang->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_lang->execute();

 
    $sql_app = "DELETE FROM application WHERE id = :id";
    $stmt_app = $db->prepare($sql_app);


    $stmt_app->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_app->execute();

  
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
