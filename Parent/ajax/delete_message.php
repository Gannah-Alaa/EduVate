<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['ParentID']) || !isset($_POST['message_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$parentId = $_SESSION['ParentID'];
$messageId = intval($_POST['message_id']);

// Only allow deleting own messages
$query = "UPDATE messages m
          JOIN chat c ON m.ChatID = c.ChatID
          SET m.IsDeleted = 'True'
          WHERE m.MessageID = ? AND m.Sender = 'Parent' AND c.ParentID = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "ii", $messageId, $parentId);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
