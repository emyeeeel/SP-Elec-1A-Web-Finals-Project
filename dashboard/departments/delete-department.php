<?php
require_once '../../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];

    $sql = "DELETE FROM departments WHERE deptid = :department_id";
    
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Department record deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete department record']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database query preparation failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
