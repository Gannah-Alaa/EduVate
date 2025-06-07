<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../connection.php';
include '../../app/chat_system.php';

if (!isset($_SESSION['ParentID']) || !isset($_GET['chat_id'])) {
    exit;
}

$chatId = $_GET['chat_id'];
$parentId = $_SESSION['ParentID'];

// Verify this chat belongs to the logged-in parent
$verifyQuery = "SELECT * FROM chat WHERE ChatID = ? AND ParentID = ?";
$stmt = mysqli_prepare($connect, $verifyQuery);
mysqli_stmt_bind_param($stmt, "ii", $chatId, $parentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    exit;
}

// Get messages
$messages = getMessages($chatId, $connect);

// Output messages HTML
if ($messages->num_rows > 0) {
    while($message = $messages->fetch_assoc()) {
        $isParent = $message['Sender'] == 'Parent';
        ?>
        <div class="message <?php echo $isParent ? 'sent' : 'received'; ?>" data-message-id="<?php echo $message['MessageID']; ?>">
            <div class="message-content position-relative">
                <?php if($message['IsDeleted'] === 'True'): ?>
                    <span class="text-muted fst-italic">This message was deleted.</span>
                <?php else: ?>
                    <?php if($message['Text']): ?>
                        <?php echo nl2br(htmlspecialchars($message['Text'])); ?>
                    <?php endif; ?>
                    <?php if($message['Media']): ?>
                        <?php
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
                        ?>
                    <?php endif; ?>
                    <?php if($isParent): ?>
                        <button class="btn btn-sm btn-link text-danger position-absolute top-0 end-0 delete-message-btn" title="Delete" data-message-id="<?php echo $message['MessageID']; ?>"><i class="fas fa-trash"></i></button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="message-time d-flex justify-content-between align-items-center">
                <span><?php echo date('M j, Y g:i a', strtotime($message['DateTime'])); ?></span>
                <?php if($isParent && $message['IsDeleted'] !== 'True'): ?>
                    <span class="ms-2 small text-<?php echo ($message['Seen'] == 'Seen') ? 'success' : 'secondary'; ?>">
                        <?php echo ($message['Seen'] == 'Seen') ? 'Seen' : 'Delivered'; ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="text-center text-muted">
        <i class="fas fa-comments fa-3x mb-3"></i>
        <p>No messages yet. Start the conversation!</p>
    </div>
    <?php
}