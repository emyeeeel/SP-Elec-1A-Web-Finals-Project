<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once '../../db_connection.php';

if (!$pdo) {
    die(json_encode(array("error" => "Database connection failed")));
}

function getColleges() {
    global $pdo;

    $sql = "SELECT collid, collfullname FROM colleges";
    try {
        $stmt = $pdo->query($sql);
        if ($stmt) {
            $colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($colleges);
        } else {
            echo json_encode(array("message" => "No colleges found"));
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(array("error" => "An error occurred while fetching colleges."));
    }
}

function getDepartments() {
    global $pdo;

    $sql = "SELECT deptid, deptcollid, deptfullname FROM departments";
    try {
        $stmt = $pdo->query($sql);
        if ($stmt) {
            $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($departments);
        } else {
            echo json_encode(array("message" => "No departments found"));
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(array("error" => "An error occurred while fetching departments."));
    }
}

if (isset($_GET['type'])) {
    if ($_GET['type'] === 'colleges') {
        getColleges();
    } elseif ($_GET['type'] === 'departments') {
        getDepartments();
    } else {
        echo json_encode(array("error" => "Invalid 'type' parameter"));
    }
} else {
    echo json_encode(array("error" => "Missing 'type' parameter"));
}
?>
