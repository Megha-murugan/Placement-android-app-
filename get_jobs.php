<?php
require_once 'config.php';

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Fetch all jobs
$sql = "SELECT j_id, title, company, location, type, salary, description, skills, posted_at FROM jobs ORDER BY posted_at DESC";
$result = $conn->query($sql);

$jobs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

sendResponse(true, 'Jobs retrieved successfully', $jobs);

$conn->close();
?>