<?php
require_once '../../db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (isset($inputData['college_id'], $inputData['full_name'], $inputData['short_name'])) {

        $collegeId = trim($inputData['college_id']);
        $fullName = trim($inputData['full_name']);
        $shortName = trim($inputData['short_name']);

        if (empty($collegeId) || empty($fullName)) {
            echo json_encode(["success" => false, "message" => "College ID and Full Name are required fields."]);
            exit();
        }

        $shortName = !empty($shortName) ? $shortName : null;

        $sql = "INSERT INTO colleges (collid, collfullname, collshortname) 
                VALUES (:college_id, :full_name, :short_name)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':college_id', $collegeId, PDO::PARAM_INT);
        $stmt->bindParam(':full_name', $fullName, PDO::PARAM_STR);
        $stmt->bindParam(':short_name', $shortName, PDO::PARAM_STR);

        $pdo->beginTransaction();

        try {
            $stmt->execute();
            $pdo->commit();
            echo json_encode(["success" => true, "message" => "College added successfully."]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("SQL Error: " . $e->getMessage());
            echo json_encode(["success" => false, "message" => "Error saving data: " . $e->getMessage()]);
        }

    } else {
        echo json_encode(["success" => false, "message" => "Missing required fields."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Method not allowed."]);
}
?>
