<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "users";

session_start();
$userId = isset($_POST['userId']) ? $_POST['userId'] : null;
$errorMessage = "";

if (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['userType'])) {
    echo "Please fill in all fields.";
    exit();
}

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$username = $_POST['username'];
$password = $_POST['password'];
$userType = $_POST['userType'];

$sql = "INSERT INTO acc (fname, lname, username, password, usertype) VALUES ('$firstName', '$lastName', '$username', '$password', '$userType')";

if ($conn->query($sql) === TRUE) {
    echo "New user added successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
