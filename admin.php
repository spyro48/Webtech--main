<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>OOP-Final Admin</title>
    <script src="assets/javascript/admin-script.js"></script>
</head>
<body>
    <nav>
        <div class="logo">OOP</div>
        <img src="../assets/images/slulog.png" id="slulog">

        <div class="navbar">
            <nav>
                <ul>
                    <li><a href="logout.php" class="icon"><img src="assets\images\logout.png" alt="logout"></a></li>
                </ul>
            </nav>
        </div>
    </nav>

    <main>
        <div class="content-container">

            <div class="table-header">
                <h2>User Table</h2>
                <button id="addbutton" onclick="toggleAddUserForm()">Add User</button>
            </div>
            
            <form id="addUserForm" class="login-form" style="display:none;">
            <div class="form-title">OOP</div>
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required><br>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required><br>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <label for="userType">User Type:</label>
            <select id="userType" name="userType" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <br>

            <button type="button" onclick="addUserToTable()">Submit</button>
            </form>

            <form id="editUserForm" class="login-form" style="display:none;">
            <div class="form-title">Edit User</div>
            <input type="hidden" id="editUserId" name="editUserId">
            <label for="editFirstName">First Name:</label>
            <input type="text" id="editFirstName" name="editFirstName" required><br>

            <label for="editLastName">Last Name:</label>
            <input type="text" id="editLastName" name="editLastName" required><br>

            <label for="editUsername">Username:</label>
            <input type="text" id="editUsername" name="editUsername" required><br>

            <label for="editPassword">Password:</label>
            <input type="password" id="editPassword" name="editPassword" required><br>

            <label for="editUserType">User Type:</label>
            <select id="editUserType" name="editUserType" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <br>

            <button type="button" onclick="updateUser()">Update</button>
            </form>

            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "users";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT id, fname, lname, username, password, usertype FROM acc ORDER BY id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Username</th><th>Password</th><th>User Type</th><th>Actions</th></tr>";

                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["id"]."</td>";
                    echo "<td>".$row["fname"]."</td>";
                    echo "<td>".$row["lname"]."</td>";
                    echo "<td>".$row["username"]."</td>";
                    echo "<td>".$row["password"]."</td>";
                    echo "<td>".$row["usertype"]."</td>";
                    echo "<td>";
                    echo "<button onclick='editUser(".$row["id"].")'>Edit</button>";
                    echo "<button onclick='removeUser(".$row["id"].")'>Remove</button>";
                    echo "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "0 results";
            }

            $conn->close();
            ?>
        </div>
    </main>

    <footer>
    <img src="../assets/images/EDITED-FOOTER.png" id="slufooter">
    </footer>
</body>
</html>
