<?php
require_once 'config.php';

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    sendResponse(false, 'Invalid request method');
}

// Get job ID
$jobId = sanitizeInput($_POST['job_id'] ?? $_GET['job_id'] ?? '');

if (empty($jobId)) {
    sendResponse(false, 'Job ID is required');
}

// Delete job
$stmt = $conn->prepare("DELETE FROM jobs WHERE j_id = ?");
$stmt->bind_param("i", $jobId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        sendResponse(true, 'Job deleted successfully');
    } else {
        sendResponse(false, 'Job not found');
    }
} else {
    sendResponse(false, 'Failed to delete job');
}

$stmt->close();
$conn->close();
?>