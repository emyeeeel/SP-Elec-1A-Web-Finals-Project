<?php
require_once '../../db_connection.php';

header('Content-Type: application/json');

$inputData = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($inputData['college_id'], $inputData['full_name'], $inputData['short_name'], $inputData['originalID'])) {
        $collegeId = trim($inputData['college_id']);
        $fullName = trim($inputData['full_name']);
        $shortName = trim($inputData['short_name']);
        $originalId = trim($inputData['originalID']);

        if (empty($collegeId) || empty($fullName)) {
            echo json_encode(["success" => false, "message" => "College ID and Full Name are required fields."]);
            exit();
        }

        $sql = "UPDATE colleges 
                SET collid = :college_id, collfullname = :full_name, collshortname = :short_name 
                WHERE collid = :original_id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':original_id', $originalId, PDO::PARAM_INT);
        $stmt->bindParam(':college_id', $collegeId, PDO::PARAM_INT);
        $stmt->bindParam(':full_name', $fullName, PDO::PARAM_STR);
        $stmt->bindParam(':short_name', $shortName, PDO::PARAM_STR);

        $pdo->beginTransaction();

        try {
            $stmt->execute();
            $pdo->commit();
            echo json_encode(["success" => true, "message" => "College updated successfully."]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("SQL Error: " . $e->getMessage());
            echo json_encode(["success" => false, "message" => "Error updating data: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Missing required fields."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Method not allowed."]);
}
?>
