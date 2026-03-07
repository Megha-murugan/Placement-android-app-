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
$email = sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validate inputs
if (empty($email) || empty($password)) {
    sendResponse(false, 'Email and password are required');
}

// Query student
$stmt = $conn->prepare("SELECT s_id, s_name, email, password, phone, college, degree, skills, resume FROM students WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    sendResponse(false, 'Invalid email or password');
}

$student = $result->fetch_assoc();

// Verify password
if (password_verify($password, $student['password'])) {
    // Remove password from response
    unset($student['password']);
    
    sendResponse(true, 'Login successful', $student);
} else {
    sendResponse(false, 'Invalid email or password');
}

$stmt->close();
$conn->close();
?>