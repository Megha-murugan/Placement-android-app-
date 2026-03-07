<?php
require_once 'config.php';

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Get job ID if specified
$jobId = $_GET['job_id'] ?? '';

if (!empty($jobId)) {
    // Get applications for specific job
    $stmt = $conn->prepare("
        SELECT r.r_id, r.j_id, r.s_id, r.applied_at,
               s.s_name, s.email, s.phone, s.college, s.degree, s.skills, s.resume,
               j.title, j.company
        FROM result r
        JOIN students s ON r.s_id = s.s_id
        JOIN jobs j ON r.j_id = j.j_id
        WHERE r.j_id = ?
        ORDER BY r.applied_at DESC
    ");
    $stmt->bind_param("i", $jobId);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Get all applications
    $sql = "
        SELECT r.r_id, r.j_id, r.s_id, r.applied_at,
               s.s_name, s.email, s.phone, s.college, s.degree, s.skills, s.resume,
               j.title, j.company
        FROM result r
        JOIN students s ON r.s_id = s.s_id
        JOIN jobs j ON r.j_id = j.j_id
        ORDER BY r.applied_at DESC
    ";
    $result = $conn->query($sql);
}

$applications = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}

sendResponse(true, 'Applications retrieved successfully', $applications);

$conn->close();
?>