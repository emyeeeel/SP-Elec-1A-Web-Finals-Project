<?php
require_once '../../db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (isset($inputData['program_id'], $inputData['full_name'], $inputData['short_name'], $inputData['college'], $inputData['department'], $inputData['originalID'])) {
        
        $programId = $inputData['program_id'];
        $fullName = $inputData['full_name'];
        $shortName = $inputData['short_name'];
        $collegeFullName = $inputData['college'];
        $departmentName = $inputData['department'];
        $originalProgramId = $inputData['originalID'];

        // If short_name is empty, set it as NULL
        $shortName = !empty($shortName) ? $shortName : null;

        if (empty($programId) || empty($fullName) || empty($collegeFullName) || empty($departmentName)) {
            echo json_encode(["success" => false, "message" => "All fields are required!"]);
            exit();
        }

        $collegeQuery = $pdo->prepare("SELECT collid FROM colleges WHERE collfullname = :collfullname");
        $collegeQuery->bindParam(':collfullname', $collegeFullName, PDO::PARAM_STR);
        $collegeQuery->execute();
        $collegeResult = $collegeQuery->fetch(PDO::FETCH_ASSOC);

        if (!$collegeResult) {
            echo json_encode(["success" => false, "message" => "Invalid college name."]);
            exit();
        }
        $collegeId = $collegeResult['collid'];

        $departmentQuery = $pdo->prepare("SELECT deptid FROM departments WHERE deptfullname = :deptfullname AND deptcollid = :collid");
        $departmentQuery->bindParam(':deptfullname', $departmentName, PDO::PARAM_STR);
        $departmentQuery->bindParam(':collid', $collegeId, PDO::PARAM_INT);
        $departmentQuery->execute();
        $departmentResult = $departmentQuery->fetch(PDO::FETCH_ASSOC);

        if (!$departmentResult) {
            echo json_encode(["success" => false, "message" => "Invalid department for the selected college."]);
            exit();
        }

        $departmentId = $departmentResult['deptid'];

        $sql = "UPDATE programs 
                SET progid = :progid, 
                    progfullname = :progfullname, 
                    progshortname = :progshortname, 
                    progcollid = :progcollid, 
                    progcolldeptid = :progcolldeptid 
                WHERE progid = :originalProgramId";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':progid', $programId, PDO::PARAM_INT);
        $stmt->bindParam(':originalProgramId', $originalProgramId, PDO::PARAM_INT);
        $stmt->bindParam(':progfullname', $fullName, PDO::PARAM_STR);
        $stmt->bindParam(':progshortname', $shortName, PDO::PARAM_STR);
        $stmt->bindParam(':progcollid', $collegeId, PDO::PARAM_INT);
        $stmt->bindParam(':progcolldeptid', $departmentId, PDO::PARAM_INT);

        $pdo->beginTransaction();

        try {
            $stmt->execute();
            $pdo->commit();
            echo json_encode(["success" => true, "message" => "Program updated successfully."]);
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
