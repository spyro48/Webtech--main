<?php
    require 'db_connection.php';
    session_start();

    $rawData = json_decode(file_get_contents('php://input'));
    $videoid = $rawData->videoid;
    $sequence = $rawData->sequence;
    $sequence = $rawData->schedule_id;
    $filepath = realpath($rawData->path);

    chmod($filepath, 0777);
    unlink($filepath);

    
    // 1) Delete the video
    $stmt = $conn->prepare("DELETE FROM uploads where id=?");
    $stmt->bind_param("i", $videoid);
    $stmt->execute(); 

    $schedule_id = 1;

    $event = "Removed a video.";
    $stmt = $conn->prepare("INSERT INTO history (`event`,user_id) VALUES (?,?)");
    $stmt->bind_param("si", $event, $_SESSION['uid']);
    $stmt->execute();
    
    // # 2) Get the last sequence number and subtract 1 if it is not equal to 1
    $stmt = $conn->prepare("SELECT id, MAX(sequence) as last_sequence FROM `uploads` WHERE schedule_id = ?");
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();
    $response = array();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (isset($row['id']) && $row['last_sequence'] != 1) {
        $new_sequence = $row['last_sequence']-1;

        $stmt = $conn->prepare("UPDATE uploads set sequence=? WHERE id=?");
        $stmt->bind_param("ii",$new_sequence,$row['id']);
        $stmt->execute(); 
    }

   
?>