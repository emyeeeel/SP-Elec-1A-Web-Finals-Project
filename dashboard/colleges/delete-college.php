<?php
require_once '../../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    error_log(print_r($data, true));

    if (isset($data['college_id'])) {
        $college_id = $data['college_id'];

        $sql = "DELETE FROM colleges WHERE collid = :college_id";
        
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':college_id', $college_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'College record deleted successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete college record']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database query preparation failed']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'college_id not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
