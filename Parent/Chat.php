<?php
session_start();

if (!isset($_SESSION['ParentID'])) {
    header("Location: login.php");
    exit;
}

include '../connection.php';
include '../app/chat_system.php';

if (!isset($_GET['chat_id'])) {
    header("Location: ParentChats.php");
    exit;
}

$chatId = $_GET['chat_id'];
$parentId = $_SESSION['ParentID'];

// Verify this chat belongs to the logged-in parent
$verifyQuery = "SELECT c.*, t.TeacherName, t.TeacherPic 
                FROM chat c 
                JOIN teachers t ON c.TeacherID = t.TeacherID 
                WHERE c.ChatID = ? AND c.ParentID = ?";
$stmt = mysqli_prepare($connect, $verifyQuery);
mysqli_stmt_bind_param($stmt, "ii", $chatId, $parentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: ParentChats.php");
    exit;
}

$chat = mysqli_fetch_assoc($result);
$teacher = getTeacher($chat['TeacherID'], $connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($teacher['TeacherName']); ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .chat-container {
            height: calc(120vh - 220px);
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            padding: 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
        }
        .message {
            margin-bottom: 15px;
            max-width: 70%;
        }
        .message.sent {
            margin-left: auto;
        }
        .message.received {
            margin-right: auto;
        }
        .message-content {
            padding: 10px 15px;
            border-radius: 15px;
            background: #e9ecef;
        }
        .message.sent .message-content {
            background: #6d9773;
            color: white;
        }
        .message-time {
            font-size: 0.8em;
            color: #6c757d;
            margin-top: 5px;
        }
        .message.sent .message-time {
            text-align: right;
        }
        .chat-input {
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
        .file-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="chat-container">
                    <div class="chat-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="../Media/<?php echo $teacher['TeacherPic'] ?? 'default.png'; ?>" 
                                 alt="Teacher" class="rounded-circle" style="width: 40px; height: 40px;">
                            <div class="ms-3">
                                <h5 class="mb-0"><?php echo htmlspecialchars($teacher['TeacherName']); ?></h5>
                                <small class="text-muted">Teacher</small>
                            </div>
                        </div>
                        <a href="ParentChats.php" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Chats
                        </a>
                    </div>
                    
                    <div class="chat-messages" id="chatMessages">
                        <!-- Messages will be loaded here via AJAX -->
                    </div>
                    
                    <div class="chat-input">
                        <form id="messageForm" enctype="multipart/form-data">
                            <input type="hidden" name="chat_id" value="<?php echo $chatId; ?>">
                            <div class="input-group">
                                <textarea name="message" class="form-control message-input" 
                                          placeholder="Type a message..." rows="1"></textarea>
                                <input type="file" name="file" id="file" class="d-none">
                                <label for="file" class="btn btn-outline-secondary">
                                    <i class="fas fa-paperclip"></i>
                                </label>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                            <div id="attachmentPreview"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadMessages(callback) {
            $.get('ajax/get_messages.php?chat_id=<?php echo $chatId; ?>', function(data) {
                $('#chatMessages').html(data);
                $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
                if (typeof callback === "function") callback();
            });
        }

        function refreshChatList() {
            if ($('#chatList').length) {
                $('#chatList').load('ParentChats.php .chat-list > *');
            }
        }

        // Only mark as seen ONCE when chat is opened
        function markAsSeenAndRefresh() {
            $.post('ajax/mark_seen.php', { chat_id: <?php echo $chatId; ?> }, function() {
                refreshChatList();
            });
        }

        // On first load, mark as seen and refresh chat list
        $(document).ready(function() {
            loadMessages();
            markAsSeenAndRefresh();
            setInterval(loadMessages, 5000);
        });

        // Handle file selection
        $('#file').change(function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = '';
                    if (file.type.startsWith('image/')) {
                        preview = '<img src="' + e.target.result + '" class="attachment-preview">';
                    } else {
                        preview = '<div class="alert alert-info">File selected: ' + file.name + '</div>';
                    }
                    $('#attachmentPreview').html(preview);
                }
                reader.readAsDataURL(file);
            }
        });

        // Handle message submission
        $('#messageForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: 'ajax/send_message.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        $('#messageForm')[0].reset();
                        $('#attachmentPreview').empty();
                        loadMessages();
                    } else {
                        alert('Error sending message: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error sending message. Please try again.');
                }
            });
        });

        // Handle delete message
        $(document).on('click', '.delete-message-btn', function(e) {
            e.preventDefault();
            var messageId = $(this).data('message-id');
            if (confirm('Are you sure you want to delete this message?')) {
                $.post('ajax/delete_message.php', { message_id: messageId }, function(response) {
                    if(response.success) {
                        loadMessages();
                    } else {
                        alert('Error deleting message');
                    }
                }, 'json');
            }
        });
    </script>
</body>
</html>