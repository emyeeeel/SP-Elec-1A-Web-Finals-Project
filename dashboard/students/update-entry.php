<?php
require_once '../../db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (isset($inputData['student_id'], $inputData['first_name'], $inputData['middle_name'], $inputData['last_name'], $inputData['college'], $inputData['program'], $inputData['year'], $inputData['originalID'])) {
        
        $studentId = $inputData['student_id'];
        $firstName = $inputData['first_name'];
        $middleName = $inputData['middle_name'];
        $lastName = $inputData['last_name'];
        $collegeFullName = $inputData['college'];
        $programFullName = $inputData['program'];
        $year = $inputData['year'];
        $originalId = $inputData['originalID'];

        $collegeQuery = $pdo->prepare("SELECT collid FROM colleges WHERE collfullname = :collfullname");
        $collegeQuery->bindParam(':collfullname', $collegeFullName, PDO::PARAM_STR);
        $collegeQuery->execute();
        $collegeResult = $collegeQuery->fetch(PDO::FETCH_ASSOC);
        
        if (!$collegeResult) {
            echo json_encode(["success" => false, "message" => "Invalid college name."]);
            exit();
        }
        $collegeId = $collegeResult['collid'];

        $programQuery = $pdo->prepare("SELECT progid FROM programs WHERE progfullname = :progfullname AND progcollid = :collid");
        $programQuery->bindParam(':progfullname', $programFullName, PDO::PARAM_STR);
        $programQuery->bindParam(':collid', $collegeId, PDO::PARAM_INT);
        $programQuery->execute();
        $programResult = $programQuery->fetch(PDO::FETCH_ASSOC);

        if (!$programResult) {
            echo json_encode(["success" => false, "message" => "Invalid program name for the selected college."]);
            exit();
        }
        $programId = $programResult['progid'];

        if (empty($studentId) || empty($firstName) || empty($lastName) || empty($collegeId) || empty($programId) || empty($year)) {
            echo json_encode(["success" => false, "message" => "All fields are required!"]);
            exit();
        }

        $programId = (int)$programId;

        $sql = "UPDATE students 
                SET studid = :studid, 
                    studfirstname = :studfirstname, 
                    studmidname = :studmidname, 
                    studlastname = :studlastname, 
                    studprogid = :studprogid, 
                    studcollid = :studcollid, 
                    studyear = :studyear 
                WHERE studid = :originalID";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':originalID', $originalId, PDO::PARAM_INT);
        $stmt->bindParam(':studid', $studentId, PDO::PARAM_INT);
        $stmt->bindParam(':studfirstname', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':studmidname', $middleName, PDO::PARAM_STR);
        $stmt->bindParam(':studlastname', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':studprogid', $programId, PDO::PARAM_INT);
        $stmt->bindParam(':studcollid', $collegeId, PDO::PARAM_INT);
        $stmt->bindParam(':studyear', $year, PDO::PARAM_STR);

        try {
            $stmt->execute();
            echo json_encode(["success" => true, "message" => "Student data updated successfully."]);
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Error updating data: " . $e->getMessage()]);
        }

    } else {
        echo json_encode(["success" => false, "message" => "Missing required fields."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Method not allowed."]);
}
?>
