<?php
require_once 'config.php';

// Enable CORS for testing
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method');
}

// Get POST data
$name = sanitizeInput($_POST['name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$password = sanitizeInput($_POST['password'] ?? '');
$phone = sanitizeInput($_POST['phone'] ?? '');
$college = sanitizeInput($_POST['college'] ?? '');
$degree = sanitizeInput($_POST['degree'] ?? '');
$skills = sanitizeInput($_POST['skills'] ?? '');
$resume = sanitizeInput($_POST['resume'] ?? '');

// Validate required fields
if (empty($name) || empty($email) || empty($password) || empty($phone) || 
    empty($college) || empty($degree)) {
    sendResponse(false, 'All required fields must be filled');
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, 'Invalid email format');
}

// Check if email already exists
$checkEmail = $conn->prepare("SELECT s_id FROM students WHERE email = ?");
$checkEmail->bind_param("s", $email);
$checkEmail->execute();
$result = $checkEmail->get_result();

if ($result->num_rows > 0) {
    sendResponse(false, 'Email already registered');
}

// Hash password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert student into database
$stmt = $conn->prepare("INSERT INTO students (s_name, email, password, phone, college, degree, skills, resume) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $name, $email, $hashedPassword, $phone, $college, $degree, $skills, $resume);

if ($stmt->execute()) {
    $studentId = $conn->insert_id;
    
    // Return student data (without password)
    sendResponse(true, 'Registration successful', [
        's_id' => $studentId,
        's_name' => $name,
        'email' => $email,
        'phone' => $phone,
        'college' => $college,
        'degree' => $degree,
        'skills' => $skills,
        'resume' => $resume
    ]);
} else {
    sendResponse(false, 'Registration failed. Please try again');
}

$stmt->close();
$conn->close();
?>