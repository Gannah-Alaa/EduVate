<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../connection.php';
include '../../app/chat_system.php';

if (!isset($_SESSION['TeacherID'])) {
    exit('Not logged in');
}

if (!isset($_GET['chat_id'])) {
    exit('No chat specified');
}

$chatId = $_GET['chat_id'];
$teacherId = $_SESSION['TeacherID'];

// Verify this chat belongs to the logged-in teacher
$verifyQuery = "SELECT ChatID FROM chat WHERE ChatID = ? AND TeacherID = ?";
$stmt = mysqli_prepare($connect, $verifyQuery);
mysqli_stmt_bind_param($stmt, "ii", $chatId, $teacherId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    exit('Invalid chat');
}

// Get messages
$messages = getMessages($chatId, $connect);

if (empty($messages)) {
    echo '<div class="text-center text-muted mt-4">No messages yet</div>';
    exit;
}

foreach ($messages as $message) {
    $isTeacher = $message['Sender'] === 'Teacher';
    $messageClass = $isTeacher ? 'sent' : 'received';
    $time = date('g:i A', strtotime($message['DateTime']));
    echo "<div class='message {$messageClass}' data-message-id='{$message['MessageID']}'>";
    echo "<div class='message-content position-relative'>";
    if ($message['IsDeleted'] === 'True') {
        echo "<span class='text-muted fst-italic'>This message was deleted.</span>";
    } else {
        if (!empty($message['Text'])) {
            echo htmlspecialchars($message['Text']);
        }
        if (!empty($message['Media'])) {
            $file_ext = strtolower(pathinfo($message['Media'], PATHINFO_EXTENSION));
            // Use web-accessible path, not relative server path
            $file_url = "/Graduation Project/uploads/" . $message['Media'];
            $file_name = htmlspecialchars($message['Media']);
            if(in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                echo '<br><a href="'.$file_url.'" download="'.$file_name.'" class="text-decoration-underline text-primary"><i class="fas fa-image"></i> Download Image</a>';
            } elseif($file_ext == "pdf") {
                echo '<br><a href="'.$file_url.'" download="'.$file_name.'" class="text-decoration-underline text-primary"><i class="fas fa-file-pdf"></i> Download PDF</a>';
            } else {
                echo '<br><a href="'.$file_url.'" download="'.$file_name.'" class="text-decoration-underline text-primary"><i class="fas fa-paperclip"></i> Download File</a>';
            }
        }
        if ($isTeacher) {
            echo "<button class='btn btn-sm btn-link text-danger position-absolute top-0 end-0 delete-message-btn' title='Delete' data-message-id='{$message['MessageID']}'><i class='fas fa-trash'></i></button>";
        }
    }
    echo "</div>";
    echo "<div class='message-time d-flex justify-content-between align-items-center'>";
    echo "<span>{$time}</span>";
    if ($isTeacher && $message['IsDeleted'] !== 'True') {
        echo "<span class='ms-2 small text-".($message['Seen'] == 'Seen' ? 'success' : 'secondary')."'>";
        echo ($message['Seen'] == 'Seen') ? 'Seen' : 'Delivered';
        echo "</span>";
    }
    echo "</div>";
    echo "</div>";
}