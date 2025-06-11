<?php
include '../connection.php';

if(!isset($_SESSION['TeacherID'])) {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['chat_id'])) {
    header("Location: Chats.php");
    exit;
}

$chatId = $_GET['chat_id'];
$teacherId = $_SESSION['TeacherID'];

// Verify this chat belongs to the logged-in teacher
$verifyQuery = "SELECT c.*, p.ParentName 
                FROM chat c
                JOIN parents p ON c.ParentID = p.ParentID
                WHERE c.ChatID = ? AND c.TeacherID = ?";
$stmt = mysqli_prepare($connect, $verifyQuery);
mysqli_stmt_bind_param($stmt, "ii", $chatId, $teacherId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0) {
    header("Location: Chats.php");
    exit;
}

$chat = mysqli_fetch_assoc($result);

// Mark messages as seen immediately when chat is opened
include '../app/chat_system.php';
markMessagesAsSeen($chatId, $connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($chat['ParentName']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
     <link rel="stylesheet" href="./CSS/bootstrap.min.css">
    <link rel="stylesheet" href="./CSS/all.min.css">
    <link rel="stylesheet" href="../fontawesome-free-6.4.0-web/css/all.min.css">

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
        <div class="card">
            <div class="chat-container">
                <div class="chat-header d-flex align-items-center">
                    <a href="Chats.php" class="btn btn-link text-dark me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <img src="../Media/<?php echo $chat['ParentPic'] ?? 'default.png'; ?>" 
                         class="rounded-circle me-2" 
                         width="40" height="40" 
                         alt="Parent">
                    <div>
                        <h5 class="mb-0"><?php echo htmlspecialchars($chat['ParentName']); ?></h5>
                    </div>
                </div>

                <div class="chat-messages" id="chatMessages">
                    <!-- Messages will be loaded here -->
                </div>

                <div class="chat-input">
                    <form id="messageForm" enctype="multipart/form-data">
                        <input type="hidden" name="chat_id" value="<?php echo $chatId; ?>">
                        <div class="input-group">
                            <textarea class="form-control" name="message" placeholder="Type a message..." rows="1"></textarea>
                            <input type="file" name="file" id="file" class="d-none">
                            <label for="file" class="btn btn-outline-secondary">
                                <i class="fas fa-paperclip"></i>
                            </label>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                        <div id="filePreview"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                $('#chatList').load('Chats.php .chat-list > *');
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
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = '';
                    if (file.type.startsWith('image/')) {
                        preview = `<img src="${e.target.result}" class="file-preview">`;
                    } else if (file.type === 'application/pdf') {
                        preview = `<div class="alert alert-info">PDF file selected: ${file.name}</div>`;
                    }
                    $('#filePreview').html(preview);
                }
                reader.readAsDataURL(file);
            }
        });

        // Handle message submission
        $('#messageForm').submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            $.ajax({
                url: 'ajax/send_message.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#messageForm')[0].reset();
                        $('#filePreview').empty();
                        loadMessages();
                    } else {
                        alert(response.message || 'Error sending message');
                    }
                },
                error: function() {
                    alert('Error sending message');
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