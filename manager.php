<?php

require 'db_connection.php';
session_start()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/javascript/manager-script.js"></script>

    <title>OOP-Final Manager</title>
</head>
<body>
    <nav>
        <div class="logo">OOP</div>
        <img src="../assets/images/slulog.png" id="slulog">

        <div class="navbar">
            <nav>
                <ul>
                <li><a href="#" class="icon arrangement-btn"><img src="assets\images\arrangement.png" alt="Arrangement"></a></li>
                <li><a href="#" class="icon history-btn"><img src="assets\images\history.png" alt="History"></a></li>
                <li><a href="#" class="icon live-btn"><img src="assets\images\live.png" alt="Live"></a></li>
                <li><a href="logout.php" class="icon"><img src="assets\images\logout.png" alt="logout"></a></li>
                </ul>
            </nav>
        </div>
    </nav>

    <main>
        <div class="popup-container" id="uploadPopup">
            <div id="popupContent"></div>
        </div>

        <div class="m-container">
            <video controls id="vidPlayer">
                <source src="assets/videos/sample.mp4" type="video/mp4">
            </video>
        </div>

        <div class="container-right">
            <h2>Arrangement Content</h2> 
            <div class="button-container"> 
            <input type="file" id="videoInput" accept="video/*">' 
            <button class="action-button" onclick="uploadVideo()">Add</button>' 
            </div>
            <table class="data-table" id="videoTable">
                <?php
                $stmt = $pdo->query('SELECT * FROM uploads');
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($rows as $row) {
                    
                ?>
                    <tr>
                    <td><?php  echo $row['title'];  ?> </td>
                    <td><?php  echo $row['uploaded_by'];  ?> </td>
                    <td><?php  echo $row['duration'];  ?> </td>
                    <td><video width="320" height="240" controls><source src="<?php echo  "assets/videos/".$row['title']?>" type="video/mp4"></video></td>

                    </tr>
                <?php }?>
            </table>
        </div>
    </main>

    <footer>
    <img src="../assets/images/EDITED-FOOTER.png" id="slufooter">
    
    </footer>
    <script src="assets/javascript/timestamp.js"></script>
</body>
</html>