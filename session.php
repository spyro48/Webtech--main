<?php
session_start();

if (isset($_GET['uid'])) {
    $_SESSION['uid'] = $_GET['uid'];
    header('Location: manager.php');
} else {
    if (!isset($_SESSION['uid'])) {
        header('Location: http://localhost:3000/logout');
    }
}

?>