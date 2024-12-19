<?php
require_once '../../db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (isset($inputData['student_id'], $inputData['first_name'], $inputData['middle_name'], $inputData['last_name'], $inputData['college'], $inputData['program'], $inputData['year'])) {
        
        $studentId = $inputData['student_id'];
        $firstName = $inputData['first_name'];
        $middleName = $inputData['middle_name'];
        $lastName = $inputData['last_name'];
        $collegeId = $inputData['college'];
        $programId = $inputData['program'];
        $year = $inputData['year'];

        if (empty($studentId) || empty($firstName) || empty($lastName) || empty($collegeId) || empty($programId) || empty($year)) {
            echo json_encode(["success" => false, "message" => "All fields are required!"]);
            exit();
        }

        $sql = "INSERT INTO students (studid, studfirstname, studmidname, studlastname, studprogid, studcollid, studyear) 
                VALUES (:studid, :studfirstname, :studmidname, :studlastname, :studprogid, :studcollid, :studyear)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':studid', $studentId);
        $stmt->bindParam(':studfirstname', $firstName);
        $stmt->bindParam(':studmidname', $middleName);
        $stmt->bindParam(':studlastname', $lastName);
        $stmt->bindParam(':studprogid', $programId);
        $stmt->bindParam(':studcollid', $collegeId);
        $stmt->bindParam(':studyear', $year);

        try {
            $stmt->execute();
            echo json_encode(["success" => true, "message" => "Student data saved successfully."]);
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Error saving data: " . $e->getMessage()]);
        }

    } else {
        echo json_encode(["success" => false, "message" => "Missing required fields."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Method not allowed."]);
}
?>
