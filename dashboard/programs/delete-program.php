<?php
require_once '../../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['program_id'])) {
    $program_id = $_POST['program_id'];

    $sql = "DELETE FROM programs WHERE progid = :program_id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':program_id', $program_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'program record deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete program record']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database query preparation failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
