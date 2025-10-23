<?php
require 'db_connection.php';
session_start();

$stmt = $conn->prepare("INSERT INTO schedule (start_time, end_time, schedule_name) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $start_time, $end_time, $schedule_name);

$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$schedule_name = $_POST['schedule_name'];
$stmt->execute();


$stmt = $conn->prepare("INSERT INTO history (`event`, user_id) VALUES (?, ?)");
$stmt->bind_param("ss", $event, $user_id);

$event = "Added new schedule.";
$user_id = $_SESSION['uid'];
$stmt->execute();



Header('Location: manager.php');
?>