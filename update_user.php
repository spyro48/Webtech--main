<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST["userId"];
    $newFirstName = $_POST["newFirstName"];
    $newLastName = $_POST["newLastName"];
    $newUsername = $_POST["newUsername"];
    $newPassword = $_POST["newPassword"];
    $newUserType = $_POST["newUserType"];

    $stmt = $conn->prepare("UPDATE acc SET fname=?, lname=?, username=?, password=?, usertype=? WHERE id=?");
    $stmt->bind_param("ssssssi", $newFirstName, $newLastName, $newUsername, $newPassword, $newUserType, $userId);
    
    if ($stmt->execute()) {
        echo "User updated successfully";
    } else {
        echo "Error updating user: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
