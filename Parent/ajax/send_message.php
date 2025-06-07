<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../connection.php';
include '../../app/chat_system.php';

header('Content-Type: application/json');

if (!isset($_SESSION['ParentID'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if (!isset($_POST['chat_id']) || !isset($_POST['message'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$chatId = $_POST['chat_id'];
$message = $_POST['message'];
$parentId = $_SESSION['ParentID'];

// Verify this chat belongs to the logged-in parent
$verifyQuery = "SELECT * FROM chat WHERE ChatID = ? AND ParentID = ?";
$stmt = mysqli_prepare($connect, $verifyQuery);
mysqli_stmt_bind_param($stmt, "ii", $chatId, $parentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid chat']);
    exit;
}

$mediaPath = '';
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

// Insert message
$insertQuery = "INSERT INTO messages (ChatID, Text, Media, Sender, DateTime, Seen, IsDeleted) 
                VALUES (?, ?, ?, 'Parent', NOW(), 'Unseen', 'False')";
$stmt = mysqli_prepare($connect, $insertQuery);
mysqli_stmt_bind_param($stmt, "iss", $chatId, $message, $mediaPath);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}