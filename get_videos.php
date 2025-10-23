<?php
require 'db_connection.php';

$stmt = $pdo->query('SELECT * FROM uploads');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    echo '<tr>';
    echo '<td>' . $row['title'] . '</td>';
    echo '<td>' . $row['uploaded_by'] . '</td>';
    echo '<td>' . $row['duration'] . '</td>';
    echo '<td><video width="320" height="240" controls><source src="' . $row['title'] . '" type="video/mp4"></video></td>';
    echo '</tr>';
}
?>