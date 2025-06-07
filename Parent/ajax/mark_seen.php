<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../connection.php';
include '../../app/chat_system.php';

if (!isset($_SESSION['ParentID']) || !isset($_POST['chat_id'])) {
    exit;
}

$chatId = intval($_POST['chat_id']);
$parentId = $_SESSION['ParentID'];

// Verify chat belongs to parent
$verifyQuery = "SELECT ChatID FROM chat WHERE ChatID = ? AND ParentID = ?";
$stmt = mysqli_prepare($connect, $verifyQuery);
mysqli_stmt_bind_param($stmt, "ii", $chatId, $parentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    markMessagesAsSeen($chatId, $connect);
}
