<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "users";

session_start();

$userId = $_POST['userId'];
$errorMessage = "";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM acc WHERE id = $userId";
if ($conn->query($sql) === FALSE) {
    $errorMessage = "Error removing user: " . $conn->error;
}

$conn->close();

if ($errorMessage !== "") {
    echo $errorMessage;
} else {
    echo "User removed successfully.";
}
?>
