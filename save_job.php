<?php
require_once 'config.php';

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method');
}

// Get POST data
$jobId = sanitizeInput($_POST['job_id'] ?? '');
$studentId = sanitizeInput($_POST['student_id'] ?? '');
$action = sanitizeInput($_POST['action'] ?? 'save'); // 'save' or 'unsave'

// Validate inputs
if (empty($jobId) || empty($studentId)) {
    sendResponse(false, 'Job ID and Student ID are required');
}

if ($action === 'save') {
    // Check if already saved
    $checkSaved = $conn->prepare("SELECT saved_id FROM saved_jobs WHERE s_id = ? AND j_id = ?");
    $checkSaved->bind_param("ii", $studentId, $jobId);
    $checkSaved->execute();
    
    if ($checkSaved->get_result()->num_rows > 0) {
        sendResponse(false, 'Job already saved');
    }
    
    // Save job
    $stmt = $conn->prepare("INSERT INTO saved_jobs (s_id, j_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $studentId, $jobId);
    
    if ($stmt->execute()) {
        sendResponse(true, 'Job saved successfully');
    } else {
        sendResponse(false, 'Failed to save job');
    }
} else {
    // Unsave job
    $stmt = $conn->prepare("DELETE FROM saved_jobs WHERE s_id = ? AND j_id = ?");
    $stmt->bind_param("ii", $studentId, $jobId);
    
    if ($stmt->execute()) {
        sendResponse(true, 'Job removed from saved');
    } else {
        sendResponse(false, 'Failed to remove saved job');
    }
}

$stmt->close();
$conn->close();
?>