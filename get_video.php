<?php
    require 'db_connection.php';
    $stmt = $pdo->query('SELECT file_path FROM uploads');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows)
?>