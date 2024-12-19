<?php
require_once '../../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    $sql = "DELETE FROM students WHERE studid = :student_id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Student record deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete student record']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database query preparation failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
