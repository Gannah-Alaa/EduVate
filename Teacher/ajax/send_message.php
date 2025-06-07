<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../connection.php';
include '../../app/chat_system.php';

header('Content-Type: application/json');

if (!isset($_SESSION['TeacherID'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if (!isset($_POST['chat_id']) || !isset($_POST['message'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$chatId = $_POST['chat_id'];
$message = $_POST['message'];
$teacherId = $_SESSION['TeacherID'];

// Verify this chat belongs to the logged-in teacher
$verifyQuery = "SELECT ChatID FROM chat WHERE ChatID = ? AND TeacherID = ?";
$stmt = mysqli_prepare($connect, $verifyQuery);
mysqli_stmt_bind_param($stmt, "ii", $chatId, $teacherId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid chat']);
    exit;
}

$mediaPath = ''; // Initialize as empty string instead of null

// Handle file upload if present
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if (!in_array($_FILES['file']['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type']);
        exit;
    }

    if ($_FILES['file']['size'] > $maxSize) {
        echo json_encode(['success' => false, 'message' => 'File too large']);
        exit;
    }

    $uploadDir = '../../uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = uniqid() . '_' . basename($_FILES['file']['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
        $mediaPath = $fileName;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
        exit;
    }
}

// Insert message with all required fields
$insertQuery = "INSERT INTO messages (ChatID, Text, Media, Seen, Sender, DateTime, IsDeleted) 
                VALUES (?, ?, ?, 'Unseen', 'Teacher', NOW(), 'False')";
$stmt = mysqli_prepare($connect, $insertQuery);
mysqli_stmt_bind_param($stmt, "iss", $chatId, $message, $mediaPath);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($connect)]);
}