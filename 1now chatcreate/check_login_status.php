<?php
session_start();

if (isset($_SESSION['email'])) {
    $response = array('isLoggedIn' => true);
} else {
    $response = array('isLoggedIn' => false);
}


header('Content-Type: application/json');
echo json_encode($response);
?>
