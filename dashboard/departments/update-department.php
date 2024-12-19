<?php
require_once '../../db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (isset($inputData['department_id'], $inputData['full_name'], $inputData['short_name'], $inputData['college'], $inputData['originalID'])) {
        
        // Extract incoming data
        $departmentId = $inputData['department_id'];
        $fullName = $inputData['full_name'];
        $shortName = $inputData['short_name'];
        $collegeFullName = $inputData['college'];
        $originalDepartmentId = $inputData['originalID'];

        // Handle short_name being blank
        // If short_name is empty, set it as NULL
        $shortName = !empty($shortName) ? $shortName : null;

        // Validation checks
        if (empty($departmentId) || empty($fullName) || empty($collegeFullName)) {
            echo json_encode(["success" => false, "message" => "All fields are required!"]);
            exit();
        }

        // Fetch the College ID from the database
        $collegeQuery = $pdo->prepare("SELECT collid FROM colleges WHERE collfullname = :collfullname");
        $collegeQuery->bindParam(':collfullname', $collegeFullName, PDO::PARAM_STR);
        $collegeQuery->execute();
        $collegeResult = $collegeQuery->fetch(PDO::FETCH_ASSOC);

        if (!$collegeResult) {
            echo json_encode(["success" => false, "message" => "Invalid college name."]);
            exit();
        }
        $collegeId = $collegeResult['collid'];

        // Update the department information
        $sql = "UPDATE departments 
                SET deptid = :deptid,
                    deptfullname = :deptfullname, 
                    deptshortname = :deptshortname, 
                    deptcollid = :deptcollid 
                WHERE deptid = :originalDepartmentId";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':deptid', $departmentId, PDO::PARAM_STR);
        $stmt->bindParam(':deptfullname', $fullName, PDO::PARAM_STR);
        $stmt->bindParam(':deptshortname', $shortName, PDO::PARAM_STR);
        $stmt->bindParam(':deptcollid', $collegeId, PDO::PARAM_INT);
        $stmt->bindParam(':originalDepartmentId', $originalDepartmentId, PDO::PARAM_INT);

        // Begin the transaction to update department
        $pdo->beginTransaction();

        try {
            $stmt->execute();
            $pdo->commit();
            echo json_encode(["success" => true, "message" => "Department updated successfully."]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("SQL Error: " . $e->getMessage());
            echo json_encode(["success" => false, "message" => "Error updating department: " . $e->getMessage()]);
        }

    } else {
        echo json_encode(["success" => false, "message" => "Missing required fields."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Method not allowed."]);
}
?>
