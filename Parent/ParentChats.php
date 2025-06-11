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

        .nav-link{
    position: relative;
    color: #ffba00;
}

.nav-link:hover{
    color: #ffba00;
}

.nav-link::after{
    content: "";
    width: 0;
    height: 3px;
    background-color: #ffba00; 
    position: absolute;
    left: 10px;
    bottom: 0;
    transition:  0.5s ;
}

.nav-link:hover::after{
    width: 70%;
}


.navbar{
    background-color: #0c3b2e;
    position: fixed;
    width: 100%;
    z-index: 20;
    top: 0;
}

.logo{
    font-size: 40px;
    font-weight: 800;
    color: #ffba00;
}

.logo:hover{
    color: #ffba00;
}


.nav-item a{
    color: #ffba00;
}

.navbar-toggler {
    border: 2px solid #ffba00;
    padding: 8px;
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 186, 0, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

.btn-outline-warning a{
    color: #ffba00;
    text-decoration: none;
}

.btn-outline-warning a:hover{
    color: #0c3b2e;
}

@media (max-width: 991px) {
    .navbar-nav {
        padding: 20px 0;
    }
    
    .nav-item {
        margin: 10px 0;
    }
    
    .after-effect a {
        display: inline-block;
        width: 100%;
        text-align: center;
    }
    
    .btn-outline-warning {
        margin: 10px auto !important;
        display: block;
    }
}


        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }
        .main-center-row {
            min-width: 90%;
            min-height: 70vh;
            position: relative;
            top: 70px;
            display: flex;
            align-items: stretch;
            justify-content: center;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            overflow: hidden;
        }
        .main-center-row > .col-md-5, .main-center-row > .col-md-7 {
            padding: 0;
            margin: 0;
        }
        .main-center-row .card {
            border-radius: 0;
            border: none;
            height: 100%;
        }
        .main-center-row .card-header {
            height: 50px;
            border-radius: 0;
        }
        @media (max-width: 991px) {
            .main-center-row {
                flex-direction: column;
                width: 98vw;
                min-height: 100vh;
                border-radius: 0;
            }
            .card {
                border-radius: 0;
            }
            .card-header {
                border-radius: 0;
            }
        }
        .card {
            border: none;
        }
        .chat-list {
            overflow-y: auto;
            padding: 0 10px;
            position: relative;
            top: 0;
            min-height: 60vh;
            /* max-height: 60vh; */
            background: #fff;
            border-radius: 0 0 18px 18px;
        }
        .chat-item {
            padding: 18px 10px;
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.2s;
            background: #fff;
            border-radius: 12px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        .chat-item:hover {
            background-color: #f7f7f7;
            cursor: pointer;
        }
        .chat-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .chat-info {
            margin-left: 15px;
            flex-grow: 1;
        }
        .chat-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #0c3b2e;
        }
        .chat-last-message {
            color: #6d9773;
            font-size: 1em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            /* max-width: 220px; */
        }
        .chat-time {
            font-size: 0.85em;
            color: #6d9773;
        }
        .unread-badge {
            background-color: #FFbA00;
            color: #0c3b2e;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 0.85em;
            font-weight: bold;
        }
        .teacher-list {
            max-height: 70vh;
            overflow-y: scroll;
        }
        .card2 .card-header{
            background-color: #0c3b2e;
            color: #FFbA00;
        }
        .teacher-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
        }
        .teacher-item:hover {
            background-color: #f8f9fa;
        }
        .btn-warning {
            background-color: #FFbA00;
            color: #0c3b2e;
            border: none;
        }
        .btn-warning:hover {
            background-color: #e69c39;
            color: #fff;
        }
        .btn-light {
            color: #0c3b2e;
        }

    </style>
</head>
<body>

<!-- start navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid ms-4">
            <a class="navbar-brand logo" href="#home"><img src="../Media/logo.png" width="250px" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-4">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#aboutus-container">AbouUs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#events">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php  #contactus">ContactUs</a>
                    </li>
                    <?php if(isset($_SESSION['ParentID'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="ParentProfile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="Subscription.php">Subscription</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="ParentChats.php">Chatting</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php if(isset($_SESSION['ParentID'])): ?>
                    <form method="post">
                        <button type="submit" name="logout" class="btn btn-outline-warning ms-3 me-4 ">Logout</button>
                    </form>
                <?php else: ?>
                    <button type="button" class="btn btn-outline-warning ms-3 me-4 "><a href="login.php">Login</a></button>
                <?php endif; ?>
            </div>
        </div>
    </nav>  <br><br> <br><br> <br>
    <!-- end navbar -->


    <div class="container mt-4">
        <div class="row">
            <div class=" mx-auto">
                <?php if ($subscriptionRequired): ?>
                    <div class="d-flex justify-content-center align-items-center" >
                        <div class="card shadow p-4">
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
                    <div class="main-center-row d-flex ">
                    <div class="card1 w-100">
                        <div class="card-header px-3  bg-warning text-dark d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Start New Chat</h4>
                            <!-- <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#teacherList">
                                <i class="fas fa-plus"></i> New Chat
                            </button> -->
                        </div>
                        <div class="collapse d-block" id="teacherList">
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

                    <div class="card2 w-100">
                        <div class="card-header px-3 d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Active Chats</h4>
                            
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