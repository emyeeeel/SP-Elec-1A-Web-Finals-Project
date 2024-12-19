<?php
session_start(); 

if (isset($_GET['category'])) {
    $_SESSION['category'] = $_GET['category'];  
    echo json_encode(['status' => 'Session set', 'category' => $_SESSION['category']]);
} else {
    echo json_encode(['status' => 'No category received']);
}

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    header("Location: dashboard/dashboard.php"); 
    exit();
}else{
    header("Location: login/login.php"); 
    exit();
}

?>
