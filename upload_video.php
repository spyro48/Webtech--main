<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "video";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




$title = $_POST['title'];
$duration = $_POST['duration'];
$uploaded_by = $_POST['uploaded_by'];

$target_dir = $_SERVER['DOCUMENT_ROOT']."\\assets\\videos\\";
$target_file = $target_dir . $title;




move_uploaded_file($_FILES['video']['tmp_name'], $target_file);

$sql = "INSERT INTO uploads (title, duration, uploaded_by) VALUES ('$title', $duration, '$uploaded_by')";
if ($conn->query($sql) === TRUE) {
    echo "Video uploaded successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

