<?php
    require 'db_connection.php';
    require 'session.php';
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/styles.css">
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <title>OOP-Final Manager</title>
</head>
<body>
    <nav>
        <div class="logo">OOP</div>
        <img src="public/images/slulog.png" id="slulog">

        <div class="navbar">
            <nav>
                <ul>
                <li><a href="manager.php" class="icon arrangement-btn"><img src="public/images/arrangement.png" alt="Arrangement"></a></li>
                <li><a href="history.php" class="icon history-btn"><img src="public/images/history.png" alt="History"></a></li>
                <li><a href="live.php" class="icon live-btn"><img src="public/images/live.png" alt="Live"></a></li>
                <li><a href="logout.php" class="icon"><img src="public/images/logout.png" alt="logout"></a></li>
                </ul>
            </nav>
        </div>
    </nav>

    <main>
        <!-- <div class="popup-container" id="uploadPopup">
            <div id="popupContent"></div>
        </div> -->
        <div class="container">
        <div class="row">
            <div class="col">
                    <!-- <video type="video/mp4" id="videoPlayer" allow="autoplay" autoplay controls>
                        <source id="videoSource">
                        Your browser does not support the video tag.
                    </video> -->
            </div>
        </div>
            <div class="row">
            

                <div class="col">
                    <h2>HISTORY</h2> 
                   
                    <table class="table table-responsive table-hover " id="scheduleTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>History ID</th>
                                <th>TIMESTAMP</th>
                                <th>EVENT</th>
                                <th>USER</th>
                            </tr>
                        </thead>
                        <?php

                            $result = $conn->query('SELECT * FROM history INNER JOIN users ON users.id = history.user_id');

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $row['history_id'] . '</td>';
                                    echo '<td>' . $row['timestamp'] . '</td>';
                                    echo '<td>' . $row['event'] . '</td>';
                                    echo '<td>' . $row['username'] . '</td>';
                                }
                            } else {
                                echo "<tr>
                                <td>0 results</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                </tr>";
                            }
                        ?>
                        
                        </table>
                </div>
                </div>
            </div>
        </div>
    </main>

</body>
<footer>
    <img src="public/images/EDITED-FOOTER.png" id="slufooter">
    <script src="public/javascript/jquery.min.js"></script>
    <script src="public/javascript/bootstrap.min.js"></script>
    <script src="public/javascript/socket.io.min.js"></script>
    <script src="public/javascript/manager-script.js"></script>
</footer>
</html>