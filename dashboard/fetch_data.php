<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

session_start(); 

require_once '../db_connection.php';

if (isset($_SESSION['category'])) {
    $category = $_SESSION['category'];
    $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');

    $sql = "";

    if ($category == 'Colleges') {
        $sql = "SELECT collid, collfullname, collshortname FROM colleges";
    } elseif ($category == 'Departments') {
        $sql = "SELECT d.deptid, d.deptfullname, d.deptshortname, c.collfullname AS college_name
                FROM departments d
                JOIN colleges c ON d.deptcollid = c.collid";
    } elseif ($category == 'Programs') {
        $sql = "SELECT p.progid, p.progfullname, p.progshortname, c.collfullname AS college_name, d.deptfullname AS department_name
                FROM programs p
                JOIN colleges c ON p.progcollid = c.collid
                JOIN departments d ON p.progcolldeptid = d.deptid";
    } elseif ($category == 'Students') {
        $sql = "SELECT s.studid, s.studfirstname, s.studlastname, s.studmidname, s.studyear, c.collfullname AS college_name, p.progfullname AS program_name
                FROM students s
                JOIN colleges c ON s.studcollid = c.collid
                JOIN programs p ON s.studprogid = p.progid";
    } else {
        echo json_encode(['error' => 'Invalid category in session.']);
        exit;
    }

    try {
        $stmt = $pdo->query($sql);

        if ($stmt && $stmt->rowCount() > 0) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data);
        } else {
            echo json_encode(['message' => 'No data available for the selected category.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database query failed: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['error' => 'Category not set in session.']);
}
?>
