<?php
include "../connection.php";
if (!isset($_SESSION['ParentID'])) {
    header("Location: login.php");
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$parentId = $_SESSION['ParentID'];

// Check subscription status
$subQuery = mysqli_query($connect, "SELECT is_subscribed FROM parents WHERE ParentID = '$parentId'");
$subRow = mysqli_fetch_assoc($subQuery);
if (!$subRow || $subRow['is_subscribed'] != 1) {
    $subscriptionRequired = true;
} else {
    $subscriptionRequired = false;
}

// Get all teachers that teach the parent's children
$teachersQuery = "SELECT DISTINCT t.* 
                 FROM teachers t
                 JOIN s_details sd ON t.TeacherID = sd.TeacherID
                 JOIN schedule s ON sd.ScheduleID = s.ScheduleID
                 JOIN students st ON st.Class = s.ClassID AND st.Grade = s.grade
                 JOIN family f ON f.StudentID = st.StudentID
                 WHERE f.ParentID = '$parentId'";
$availableTeachers = mysqli_query($connect, $teachersQuery);

// Get existing chats
$chatsQuery = "SELECT c.*, t.TeacherName, t.TeacherPic, 
               (SELECT Text FROM messages m WHERE m.ChatID = c.ChatID ORDER BY m.DateTime DESC LIMIT 1) as LastMessage,
               (SELECT DateTime FROM messages m WHERE m.ChatID = c.ChatID ORDER BY m.DateTime DESC LIMIT 1) as LastMessageTime
               FROM chat c
               JOIN teachers t ON c.TeacherID = t.TeacherID
               WHERE c.ParentID = '$parentId'
               ORDER BY LastMessageTime DESC";
$chats = mysqli_query($connect, $chatsQuery);

// Handle new chat creation
if(isset($_GET['teacher_id'])) {
    $teacherId = mysqli_real_escape_string($connect, $_GET['teacher_id']);
    
    // Check if chat already exists
    $checkQuery = "SELECT ChatID FROM chat WHERE ParentID = '$parentId' AND TeacherID = '$teacherId'";
    $checkResult = mysqli_query($connect, $checkQuery);
    
    if(mysqli_num_rows($checkResult) == 0) {
        // Create new chat
        $createQuery = "INSERT INTO chat (ParentID, TeacherID) VALUES ('$parentId', '$teacherId')";
        mysqli_query($connect, $createQuery);
    }
    
    // Redirect to chat page
    $chatId = mysqli_fetch_assoc($checkResult)['ChatID'];
    header("Location: Chat.php?chat_id=" . $chatId);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats - Parent Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .chat-list {
            max-height: 600px;
            overflow-y: auto;
        }
        .chat-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s;
        }
        .chat-item:hover {
            background-color: #f8f9fa;
        }
        .chat-item.active {
            background-color: #e9ecef;
        }
        .chat-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .chat-info {
            margin-left: 15px;
        }
        .chat-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .chat-last-message {
            color: #6c757d;
            font-size: 0.9em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
        .chat-time {
            font-size: 0.8em;
            color: #adb5bd;
        }
        .unread-badge {
            background-color: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 0.8em;
        }
        .teacher-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .teacher-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s;
        }
        .teacher-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <?php if ($subscriptionRequired): ?>
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
                        <div class="card shadow p-4" style="max-width: 400px; border: 1px solid #ffe082;">
                            <div class="text-center">
                                <i class="fas fa-lock fa-3x text-warning mb-3"></i>
                                <h4 class="mb-2 text-dark">Subscription Required</h4>
                                <p class="text-muted mb-4">
                                    You must be subscribed to access the chat system.<br>
                                    Subscribe now to connect with your child's teachers!
                                </p>
                                <a href="Subscription.php" class="btn btn-warning w-100">
                                    <i class="fas fa-star"></i> Subscribe Now
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Start New Chat</h4>
                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#teacherList">
                                <i class="fas fa-plus"></i> New Chat
                            </button>
                        </div>
                        <div class="collapse" id="teacherList">
                            <div class="card-body p-0">
                                <div class="teacher-list">
                                    <?php if (mysqli_num_rows($availableTeachers) > 0): ?>
                                        <?php while ($teacher = mysqli_fetch_assoc($availableTeachers)): ?>
                                            <a href="?teacher_id=<?php echo $teacher['TeacherID']; ?>" 
                                               class="teacher-item d-flex align-items-center text-decoration-none text-dark">
                                                <img src="../Media/<?php echo $teacher['TeacherPic'] ?? 'default.png'; ?>" 
                                                     alt="Teacher" class="chat-avatar">
                                                <div class="chat-info">
                                                    <h6 class="chat-name mb-0">
                                                        <?php echo htmlspecialchars($teacher['TeacherName']); ?>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <?php 
                                                        $subjectQuery = "SELECT SubjectName FROM subjects WHERE SubjectID = '{$teacher['Subject']}'";
                                                        $subjectResult = mysqli_query($connect, $subjectQuery);
                                                        $subject = mysqli_fetch_assoc($subjectResult);
                                                        echo htmlspecialchars($subject['SubjectName']);
                                                        ?>
                                                    </small>
                                                </div>
                                            </a>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <div class="text-center p-4">
                                            <p class="text-muted">No teachers available to chat with.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Active Chats</h4>
                            <a href="dashboard.php" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="chat-list" id="chatList">
                                <?php if (mysqli_num_rows($chats) > 0): ?>
                                    <?php while ($chat = mysqli_fetch_assoc($chats)): 
                                        // Get unread count
                                        $unreadQuery = "SELECT COUNT(*) as count FROM messages 
                                                      WHERE ChatID = '{$chat['ChatID']}' 
                                                      AND Seen = 'Unseen' 
                                                      AND Sender = 'Teacher'";
                                        $unreadResult = mysqli_query($connect, $unreadQuery);
                                        $unreadCount = mysqli_fetch_assoc($unreadResult)['count'];
                                    ?>
                                        <a href="Chat.php?chat_id=<?php echo $chat['ChatID']; ?>" 
                                           class="chat-item d-flex align-items-center text-decoration-none text-dark">
                                            <img src="../Media/<?php echo $chat['TeacherPic'] ?? 'default.png'; ?>" 
                                                 alt="Teacher" class="chat-avatar">
                                            <div class="chat-info flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="chat-name mb-0">
                                                        <?php echo htmlspecialchars($chat['TeacherName']); ?>
                                                        <?php if ($unreadCount > 0): ?>
                                                            <span class="ms-2 text-danger" title="New message">
                                                                <i class="fas fa-bell"></i> New message
                                                            </span>
                                                        <?php endif; ?>
                                                    </h6>
                                                    <span class="chat-time">
                                                        <?php 
                                                        if($chat['LastMessageTime']) {
                                                            $time = strtotime($chat['LastMessageTime']);
                                                            $now = time();
                                                            $diff = $now - $time;
                                                            
                                                            if($diff < 60) {
                                                                echo "Just now";
                                                            } elseif($diff < 3600) {
                                                                echo floor($diff/60) . "m ago";
                                                            } elseif($diff < 86400) {
                                                                echo floor($diff/3600) . "h ago";
                                                            } else {
                                                                echo date('M j, Y', $time);
                                                            }
                                                        }
                                                        ?>
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="chat-last-message mb-0">
                                                        <?php echo htmlspecialchars($chat['LastMessage'] ?? 'No messages yet'); ?>
                                                    </p>
                                                    <?php if ($unreadCount > 0): ?>
                                                        <span class="unread-badge"><?php echo $unreadCount; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="text-center p-4">
                                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No active chats. Click "New Chat" to start a conversation with a teacher.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // Refresh chat list every 10 seconds for notifications
        setInterval(function() {
            $('#chatList').load('ParentChats.php .chat-list > *');
        }, 10000);
    </script>
</body>
</html>