<?php
require_once '../../db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (isset($inputData['program_id'], $inputData['full_name'], $inputData['short_name'], $inputData['college'], $inputData['department'])) {
        
        $programId = $inputData['program_id'];
        $fullName = $inputData['full_name'];
        $shortName = $inputData['short_name'];
        $collegeId = $inputData['college'];
        $departmentId = $inputData['department'];

        if (empty($programId) || empty($fullName) || empty($shortName) || empty($collegeId) || empty($departmentId)) {
            echo json_encode(["success" => false, "message" => "All fields are required!"]);
            exit();
        }

        $sql = "INSERT INTO programs (progid, progfullname, progshortname, progcollid, progcolldeptid) 
                VALUES (:program_id, :full_name, :short_name, :college_id, :department_id)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':program_id', $programId);
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':short_name', $shortName);
        $stmt->bindParam(':college_id', $collegeId);
        $stmt->bindParam(':department_id', $departmentId);

        $pdo->beginTransaction();

        try {
            $stmt->execute();
            $pdo->commit();
            echo json_encode(["success" => true, "message" => "Program added successfully."]);
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
