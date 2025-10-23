<?php
require 'db_connection.php';
session_start();


function upload($conn, $sequence, $file, $tmp_name) {

  $target_dir = "public/videos/";

  $target_file = $target_dir . $file;
  $url_file = $target_dir . urlencode($file);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Check if file already exists
  if (file_exists($target_file)) {
    echo "<script>alert('Sorry, file already exists.')</script>";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "mp4" && $imageFileType != ".mkv" && $imageFileType != ".webm") {
    echo "<script>alert('Sorry, only videos are allowed.')</script>";
    $uploadOk = 0;
  }

  if ($uploadOk != 0) {
    if (!move_uploaded_file($tmp_name, $target_file)) {
      echo "<script>alert('Sorry, there was an error uploading your file.')</script>";
    } else {
      $new_sequence = 1;

      $stmt = $conn->prepare("SELECT id, MAX(sequence) as last_sequence FROM `uploads` WHERE schedule_id = ?");
      $stmt->bind_param("i", $_POST['schedule_id']);
      $stmt->execute();
      $response = array();

      $result = $stmt->get_result();
      $row = $result->fetch_assoc();

      if (isset($row['id']) && $row['last_sequence'] != 1) {
        $new_sequence = $row['last_sequence']+1;
      }


      $stmt = $conn->prepare("INSERT INTO uploads (title,file_path, schedule_id, user_id, sequence) VALUES (?,?,?,?,?)");
      $stmt->bind_param("ssiii", $_POST['title'], $url_file, $_POST['schedule_id'], $_SESSION['uid'], $new_sequence);
      $stmt->execute();

      $event = "Uploaded video to ".$target_file;

      $stmt = $conn->prepare("INSERT INTO history (`event`,user_id) VALUES (?,?)");
      $stmt->bind_param("si", $event, $_SESSION['uid']);
      $stmt->execute();
    }
  } 

}
$names = $_FILES['files']['name'];
$tmp_names = $_FILES['files']['tmp_name'];
$filecount = count($names);

if (isset($_POST['schedule_id']) && $_POST['schedule_id']) {
  for ($x = 0; $x < $filecount; $x++) {
    $extension = $lastElement = end(explode('.', $names[$x]));
    $new_filename = $_POST['title'] . "-" . $x . '.'. $extension;
    upload($conn, $x+1, $new_filename, $tmp_names[$x]);
  }
}
echo "<script>window.location.href = 'manager.php';</script>";




?>