<?php
session_start();

unset($_SESSION['user_id']);
unset($_SESSION['username']);
unset($_SESSION['category']); 

session_destroy();

echo json_encode(['status' => 'success', 'message' => 'You have logged out successfully.']);
?>
