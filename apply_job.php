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

// Validate inputs
if (empty($jobId) || empty($studentId)) {
    sendResponse(false, 'Job ID and Student ID are required');
}

// Check if job exists
$checkJob = $conn->prepare("SELECT j_id FROM jobs WHERE j_id = ?");
$checkJob->bind_param("i", $jobId);
$checkJob->execute();
if ($checkJob->get_result()->num_rows === 0) {
    sendResponse(false, 'Job not found');
}

// Check if student exists
$checkStudent = $conn->prepare("SELECT s_id FROM students WHERE s_id = ?");
$checkStudent->bind_param("i", $studentId);
$checkStudent->execute();
if ($checkStudent->get_result()->num_rows === 0) {
    sendResponse(false, 'Student not found');
}

// Check if already applied
$checkApplied = $conn->prepare("SELECT r_id FROM result WHERE j_id = ? AND s_id = ?");
$checkApplied->bind_param("ii", $jobId, $studentId);
$checkApplied->execute();
if ($checkApplied->get_result()->num_rows > 0) {
    sendResponse(false, 'You have already applied for this job');
}

// Insert application
$stmt = $conn->prepare("INSERT INTO result (j_id, s_id) VALUES (?, ?)");
$stmt->bind_param("ii", $jobId, $studentId);

if ($stmt->execute()) {
    sendResponse(true, 'Application submitted successfully', [
        'r_id' => $conn->insert_id,
        'j_id' => $jobId,
        's_id' => $studentId
    ]);
} else {
    sendResponse(false, 'Failed to submit application');
}

$stmt->close();
$conn->close();
?>