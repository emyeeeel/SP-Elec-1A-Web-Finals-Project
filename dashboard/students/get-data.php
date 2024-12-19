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

function getPrograms() {
    global $pdo;

    $sql = "SELECT progid, progcollid, progfullname FROM programs";
    try {
        $stmt = $pdo->query($sql);
        if ($stmt) {
            $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($programs);
        } else {
            echo json_encode(array("message" => "No programs found"));
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(array("error" => "An error occurred while fetching programs."));
    }
}

if (isset($_GET['type'])) {
    if ($_GET['type'] === 'colleges') {
        getColleges();
    } elseif ($_GET['type'] === 'programs') {
        getPrograms();
    } else {
        echo json_encode(array("error" => "Invalid 'type' parameter"));
    }
} else {
    echo json_encode(array("error" => "Missing 'type' parameter"));
}
?>
