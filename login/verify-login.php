<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

session_start(); 

require_once '../db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);

$username = isset($data['username']) ? $data['username'] : '';
$password = isset($data['pass']) ? $data['pass'] : '';

if (empty($username) || empty($password)) {
    echo json_encode([
        'success' => false,
        'message' => 'Username and password are required.'
    ]);
    exit;
}

$query = "SELECT * FROM users WHERE username = :username LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['userid'];  
        $_SESSION['username'] = $user['username'];  

        echo json_encode([
            'success' => true,
            'message' => 'Login successful.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Incorrect username or password.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'User not found.'
    ]);
}

?>