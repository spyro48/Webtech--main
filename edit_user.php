<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_POST['action'] === 'getUserById') {
    $id = $_POST['id'];

    $sql = "SELECT * FROM acc WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} elseif ($_POST['action'] === 'updateUserById') {
    $id = $_POST['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    $sql = "UPDATE acc SET fname='$firstName', lname='$lastName', username='$username', password='$password', usertype='$userType' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => 'User updated successfully']);
    } else {
        echo json_encode(['error' => 'Error updating user: ' . $conn->error]);
    }
}

$conn->close();
?>
