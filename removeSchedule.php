<?php
require 'db_connection.php';
session_start();

echo $_SESSION['uid'];

$stmt = $conn->prepare("SELECT * FROM uploads where schedule_id = ?");
$stmt->bind_param("i", $_POST["schedule_id"]);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $filepath = realpath($row['file_path']);
        chmod($filepath, 0777);
        unlink($filepath);

        $event = "Removed video on $filepath";

        $stmt = $conn->prepare("INSERT INTO history (`event`,user_id) VALUES (?,?)");
        $stmt->bind_param("si", $event, $_SESSION['uid']);
        $stmt->execute();
    }
}

$stmt = $conn->prepare("DELETE FROM uploads where schedule_id = ?");
$stmt->bind_param("i", $_POST["schedule_id"]);
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM schedule where schedule_id = ?");
$stmt->bind_param("i", $_POST["schedule_id"]);
$stmt->execute();



$event = "Removed schedule.";

$stmt = $conn->prepare("INSERT INTO history (`event`,user_id) VALUES (?,?)");
$stmt->bind_param("si", $event, $_SESSION['uid']);
$stmt->execute();


Header('Location: manager.php');
?>