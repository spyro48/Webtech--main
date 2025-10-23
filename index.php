<?php

$host="localhost";
$user="root";
$password="";
$db="users";

session_start();

$data=mysqli_connect($host, $user, $password, $db);
if($data===false){
    die("connection failed");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prevent SQL Injection
    $username = mysqli_real_escape_string($data, $username);
    $password = mysqli_real_escape_string($data, $password);

    $sql = "SELECT * FROM acc WHERE username='" . $username . "' AND password='" . $password . "'";
    $result = mysqli_query($data, $sql);

    if ($result) {
        $row = mysqli_fetch_array($result);

        if ($row && isset($row["usertype"])) {
            if ($row["usertype"] == "user") {
                $_SESSION["username"] = $username;
                $_SESSION["user_id"] = $row["id"];
                header("location: manager.php");
                exit();
            } elseif ($row["usertype"] == "admin") {
                $_SESSION["username"] = $username;
                $_SESSION["user_id"] = $row["id"];
                header("location: admin.php");
                exit();
            } else {
                
                echo '<script>alert("Incorrect username or password.");</script>';
            }
        } else {
          
            echo '<script>alert("Incorrect username or password.");</script>';
        }
    } else {
       
        echo '<script>alert("Error executing query.");</script>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>OOP-Final</title>
    <script src="assets/javascript/login.js"></script>
    <script src="assets/javascript/videoControl.js"></script>
    

</head>
<body>
    <nav>
        <div class="logo">OOP</div>
        <img src="../assets/images/slulog.png" id="slulog">

        <div class="navbar">
            <nav>
                <ul>
                    <li><a href="#" class="icon" onclick="toggleLoginForm()"><img src="assets/images/profile.png" alt="Login"></a></li>
                </ul>
            </nav>
        </div>

        <div class="login-form" id="loginForm">
            <form method="post" action="#">
                <div class="form-title">OOP</div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Email/username" required>
            
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password" required>

                <!-- Add this inside your form -->
                <div class="remember-forgot">
                <input type="checkbox" id="rememberMe" name="rememberMe">
                <label for="rememberMe"> Remember me </label>
                <a href="#" class="forgot-password" onclick="showForgotPassword()">Forgot Password?</a>
                </div>

            
                <button type="button" class="close-btn" onclick="toggleLoginForm()">Close</button>
                <button type="submit" value="Login">Login</button>
            </form>
        </div>

    </nav>

    <main>
        <div class="content-container">
            <video id="vidPlayer" autoplay controls mute>
                <source src="assets/videos/sample.mp4" type="video/mp4">
            </video>
        </div>
    </main>

    <footer>
    <img src="../assets/images/EDITED-FOOTER.png" id="slufooter">
    </footer>
    <script src="assets/javascript/timestamp.js"></script>
</body>



<script>
document.addEventListener("DOMContentLoaded", function () {
    // Check if the 'rememberMe' cookie exists
    var rememberMeChecked = getCookie("rememberMe") === "true";

    // If 'rememberMe' is checked, autofill the username and password
    if (rememberMeChecked) {
        var storedUsername = getCookie("username");
        var storedPassword = getCookie("password");

        if (storedUsername && storedPassword) {
            document.getElementById('username').value = storedUsername;
            document.getElementById('password').value = storedPassword;
        }
    }

    // Check the 'Remember Me' checkbox if needed
    document.getElementById('rememberMe').checked = rememberMeChecked;
});

// Function to set a cookie
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

// Function to get the value of a cookie
function getCookie(name) {
    var nameEQ = name + "=";
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1, cookie.length);
        }
        if (cookie.indexOf(nameEQ) === 0) {
            return cookie.substring(nameEQ.length, cookie.length);
        }
    }
    return null;
}

// Add an event listener to handle changes in the 'Remember Me' checkbox
document.getElementById('rememberMe').addEventListener('change', function () {
    // Save the state of 'Remember Me' checkbox in a cookie
    setCookie("rememberMe", this.checked, 365);

    // If the checkbox is checked, save the username and password in cookies
    if (this.checked) {
        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;
        setCookie("username", username, 365);
        setCookie("password", password, 365);
    } else {
        // If the checkbox is unchecked, clear the username and password cookies
        setCookie("username", "", -1);
        setCookie("password", "", -1);
    }
});
</script>

<script>
function showForgotPassword() {
    // Display an alert with the message
    alert("Contact your administrator");
}
</script>


</html>
