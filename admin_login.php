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

// Query admin
$stmt = $conn->prepare("SELECT admin_id, email, password FROM admin WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    sendResponse(false, 'Invalid admin credentials');
}

$admin = $result->fetch_assoc();

// Check password (plain text comparison for simplicity, use password_verify in production)
if ($password === $admin['password']) {
    sendResponse(true, 'Admin login successful', [
        'admin_id' => $admin['admin_id'],
        'email' => $admin['email']
    ]);
} else {
    sendResponse(false, 'Invalid admin credentials');
}

$stmt->close();
$conn->close();
?>