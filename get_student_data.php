<?php
require_once 'config.php';

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Get student ID
$studentId = $_GET['student_id'] ?? '';
$type = $_GET['type'] ?? 'saved'; // 'saved' or 'applied'

if (empty($studentId)) {
    sendResponse(false, 'Student ID is required');
}

if ($type === 'saved') {
    // Get saved jobs
    $stmt = $conn->prepare("
        SELECT j.j_id, j.title, j.company, j.location, j.type, j.salary, j.description, j.skills, j.posted_at
        FROM saved_jobs sj
        JOIN jobs j ON sj.j_id = j.j_id
        WHERE sj.s_id = ?
        ORDER BY sj.saved_at DESC
    ");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $jobs = [];
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
    
    sendResponse(true, 'Saved jobs retrieved successfully', $jobs);
    
} else if ($type === 'applied') {
    // Get applied jobs
    $stmt = $conn->prepare("
        SELECT j.j_id, j.title, j.company, j.location, j.type, j.salary, j.description, j.skills, j.posted_at, r.applied_at
        FROM result r
        JOIN jobs j ON r.j_id = j.j_id
        WHERE r.s_id = ?
        ORDER BY r.applied_at DESC
    ");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $jobs = [];
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
    
    sendResponse(true, 'Applied jobs retrieved successfully', $jobs);
} else {
    sendResponse(false, 'Invalid type parameter');
}

$stmt->close();
$conn->close();
?>