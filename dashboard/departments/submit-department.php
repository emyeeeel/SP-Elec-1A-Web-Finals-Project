<?php
require_once '../../db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (isset($inputData['department_id'], $inputData['full_name'], $inputData['short_name'], $inputData['college'])) {
        
        $departmentId = trim($inputData['department_id']);
        $fullName = trim($inputData['full_name']);
        $shortName = trim($inputData['short_name']);
        $collegeId = (int) $inputData['college'];

        $shortName = !empty($shortName) ? $shortName : null;

        if (empty($departmentId) || empty($fullName) || empty($collegeId)) {
            echo json_encode(["success" => false, "message" => "Department ID, Full Name, and College are required fields."]);
            exit();
        }

        $sql = "INSERT INTO departments (deptid, deptfullname, deptshortname, deptcollid) 
                VALUES (:department_id, :full_name, :short_name, :college_id)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':department_id', $departmentId);
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':short_name', $shortName);
        $stmt->bindParam(':college_id', $collegeId);

        $pdo->beginTransaction();

        try {
            $stmt->execute();
            $pdo->commit();
            echo json_encode(["success" => true, "message" => "Department added successfully."]);
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
