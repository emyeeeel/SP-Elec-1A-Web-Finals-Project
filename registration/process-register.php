<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once '../db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);

$username = isset($data['username']) ? $data['username'] : '';
$password = isset($data['pass']) ? $data['pass'] : '';

$tableExistsQuery = "
    SELECT COUNT(*) 
    FROM information_schema.tables 
    WHERE table_schema = 'usjr' 
    AND table_name = 'users'
";
$tableExistsStmt = $pdo->query($tableExistsQuery);
$tableExists = $tableExistsStmt->fetchColumn() > 0;

if (!$tableExists) {
    try {
        $createTableQuery = "
            CREATE TABLE usjr.users (
                userid INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL
            );
        ";
        $pdo->exec($createTableQuery);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error creating users table: ' . $e->getMessage()
        ]);
        exit;
    }
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM usjr.users WHERE username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$userCount = $stmt->fetchColumn();

if ($userCount > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Username already exists.'
    ]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO usjr.users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Registration successful!'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error during registration: ' . $e->getMessage()
    ]);
}
?>
