<?php
    require 'db_connection.php';
    session_start();

    $rawData = json_decode(file_get_contents('php://input'));
    $videoid = $rawData->videoid;

    $stmt = $pdo->prepare("UPDATE uploads where id=?");
    $stmt->execute([$videoid]); 
    $row = $stmt->fetch();

?>