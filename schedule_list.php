<?php
    require "db_connection.php";
    session_start();

    $rawData = json_decode(file_get_contents('php://input'));
    $schedule_id = $rawData->schedule_id;
    // $schedule_id = 1;

    $query = "SELECT uploads.id, 
                    CONCAT(users.fname, ' ', users.lname) as uploaded_by, 
                    title, file_path, sequence, s.schedule_id
            FROM uploads 
            INNER JOIN schedule s on s.schedule_id = uploads.schedule_id 
            INNER JOIN users on users.id = uploads.user_id
            WHERE s.schedule_id = ?
            ORDER BY sequence";


    $stmt = $conn->prepare($query); 
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();


    $response = array();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $json_result = json_encode($data);

        $response['success'] = true;
        $response['message'] = 'Data found';
        $response['data'] = $data;
        $response['dataCount'] = count($data);

    } else {

        $response['success'] = false;
        $response['message'] = 'No data found';
        $response['data'] = array();
        $response['dataCount'] = 0;
    }

    $json_result = json_encode($response);

    header('Content-Type: application/json');
    echo $json_result;
    

?>