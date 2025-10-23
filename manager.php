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
                    <h2>Arrangement Content</h2> 
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                        NEW
                    </button>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVideoModal">
                            ADD VIDEO
                    </button>
                    <table class="table table-responsive table-hover " id="scheduleTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>Schedule Name</th>
                                <th>Start Time</th>
                                <th>End Time Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php

                            $result = $conn->query('SELECT * FROM schedule');

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $row['schedule_name'] . '</td>';
                                    echo '<td>' . $row['start_time'] . '</td>';
                                    echo '<td>' . $row['end_time'] . '</td>';
                                    echo '<td>
                                            <button 
                                                type="button" 
                                                data-id="' . $row['schedule_id'] . '" 
                                                class="btn btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewScheduleModal"
                                            > View Details </button>
                                            <button 
                                                type="button" 
                                                data-id="' . $row['schedule_id'] . '" 
                                                class="btn btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#removeScheduleModal"
                                            > Remove </button>
                                         </td>';
                                    echo '</tr>';
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

    <div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addScheduleModalLabel">Add Schedule</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addScheduleForm" action="schedule.php" method="post">
    
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Schedule Name:</span>
                        <input class="form-control" type="text" name="schedule_name" id="schedule_name" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Start Time:</span>
                        <input class="form-control" type="time" name="start_time" id="start_time" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">End Time:</span>
                        <input class="form-control" type="time" name="end_time" id="end_time" required>
                    </div>
                </form>     
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button form="addScheduleForm" type="submit" class="btn btn-success">ADD</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewScheduleModal" tabindex="-1" role="dialog" aria-labelledby="viewScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewScheduleModalLabel">Video Playlist</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-responsive table-hover " id="videoTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>Sequence #</th>
                                <th>Title</th>
                                <th>File Path</th>
                                <th>Uploaded By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="videoTableBody">
                        </tbody>
                    </table>
                
                </div>
                <div class="modal-footer">
                    <input id="schedule_id" type="hidden"></input>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
     
    <div class="modal fade" id="addVideoModal" tabindex="-1" role="dialog" aria-labelledby="addVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVideoModalLabel">Add Video</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="uploadVideoForm" action="upload.php" method="post" enctype="multipart/form-data">
    
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Title:</span>
                        <input class="form-control" type="text" name="title" id="videotitle" required>
                    </div>
                    <div class="input-group mb-3"> 
                        <input class="form-control" type="file" name="files[]" id="videoInput" required multiple>
                    </div>
                    <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Schedule:</span>
                    <select class="form-control" id="schedule-list" name="schedule_id">
                        <?php
                            $result = $conn->query('SELECT schedule_id, CONCAT(schedule_name, " (", start_time, "-", end_time, ")") as schedule FROM schedule');
                            if ($result->num_rows > 0) {
                                // output data of each row
                                while($row = $result->fetch_assoc()) {
                                    echo '<option value=' . $row['schedule_id'] . '>'.$row['schedule'].'</option>';
                                }
                              } else {
                                echo "<option value=''>Please add new schedule first.</option>";
                              }
                        ?>
                            
                        </select>
                    </div>
                </form>     
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button form="uploadVideoForm" type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="removeScheduleModal" tabindex="-1" role="dialog" aria-labelledby="removeScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeScheduleModalLabel">Remove Video</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this schedule?</p>
                    
            </div>
            <div class="modal-footer">
            <form id="removeScheduleForm" action="removeSchedule.php" method="post" enctype="multipart/form-data">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
                <button name="schedule_id" id="removeScheduleConfirm" form="removeScheduleForm" type="submit" class="btn btn-danger">Yes</button>
            </form>     
            </div>
            </div>
        </div>
    </div>

</body>
<footer>
    <img src="public/images/EDITED-FOOTER.png" id="slufooter">
    <script src="public/javascript/jquery.min.js"></script>
    <script src="public/javascript/bootstrap.min.js"></script>
    <script src="public/javascript/socket.io.min.js"></script>
    <script src="public/javascript/manager-script.js"></script>
</footer>
</html>