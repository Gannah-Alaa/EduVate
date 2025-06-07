<?php
include '../connection.php';

if(!isset($_SESSION['TeacherID'])) {
    header("Location: login.php");
    exit;
}

$teacherId = $_SESSION['TeacherID'];

// Handle new chat creation
if (isset($_GET['parent_id'])) {
    $parentId = mysqli_real_escape_string($connect, $_GET['parent_id']);
    // Check if chat already exists
    $checkQuery = "SELECT ChatID FROM chat WHERE ParentID = '$parentId' AND TeacherID = '$teacherId'";
    $checkResult = mysqli_query($connect, $checkQuery);
    if(mysqli_num_rows($checkResult) == 0) {
        $createQuery = "INSERT INTO chat (ParentID, TeacherID) VALUES ('$parentId', '$teacherId')";
        mysqli_query($connect, $createQuery);
        $chatId = mysqli_insert_id($connect);
    } else {
        $chatId = mysqli_fetch_assoc($checkResult)['ChatID'];
    }
    header("Location: Chat.php?chat_id=" . $chatId);
    exit;
}

// Get all chats for this teacher
$chatsQuery = "SELECT c.*, p.ParentName, 
               (SELECT COUNT(*) FROM messages m 
                WHERE m.ChatID = c.ChatID 
                AND m.Sender = 'Parent' 
                AND m.Seen = 'Unseen') as unread_count,
               (SELECT Text FROM messages 
                WHERE ChatID = c.ChatID 
                ORDER BY DateTime DESC LIMIT 1) as last_message,
               (SELECT DateTime FROM messages 
                WHERE ChatID = c.ChatID 
                ORDER BY DateTime DESC LIMIT 1) as last_message_time
               FROM chat c
               JOIN parents p ON c.ParentID = p.ParentID
               WHERE c.TeacherID = ?
               ORDER BY last_message_time DESC";
$stmt = mysqli_prepare($connect, $chatsQuery);
mysqli_stmt_bind_param($stmt, "i", $teacherId);
mysqli_stmt_execute($stmt);
$chats = mysqli_stmt_get_result($stmt);

// Handle parent search
$searchResults = [];
if (isset($_GET['search_parent']) && strlen(trim($_GET['search_parent'])) > 0) {
    $search = mysqli_real_escape_string($connect, $_GET['search_parent']);
    $searchQuery = "SELECT ParentID, ParentName FROM parents WHERE ParentName LIKE '%$search%'";
    $searchResults = mysqli_query($connect, $searchQuery);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Chats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #eaf6f0;
        }
        .card {
            border: none;
        }
        .chat-list {
            max-height: 70vh;
            overflow-y: auto;
            padding: 0 10px;
        }
        .chat-item {
            padding: 18px 10px;
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.2s;
            background: #fff;
            border-radius: 12px;
            margin-bottom: 8px;
        }
        .chat-item:hover {
            background-color: #f7f7f7;
            cursor: pointer;
        }
        .unread-badge {
            background-color: #FFbA00;
            color: #0c3b2e;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 0.85em;
            font-weight: bold;
        }
        .last-message {
            color: #6d9773;
            font-size: 1em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 220px;
        }
        .chat-time {
            font-size: 0.85em;
            color: #6d9773;
        }
        .card-header {
            border-bottom: none;
        }
        @media (max-width: 991px) {
            .col-md-5, .col-md-7 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body style="background: #f8f9fa;">
    <div class="container-fluid py-5" style="min-height:100vh; background: #eaf6f0;">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 col-xl-7">
                <a href="TeacherProfile.php" class="btn btn-warning mb-3" style="background:#FFbA00; color:#0c3b2e; font-weight:600;">
                    <i class="fas fa-arrow-left"></i> Back to Profile
                </a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="row g-4">
                    <!-- Start New Chat -->
                    <div class="col-md-5">
                        <div class="card shadow" style="border-radius:18px;">
                            <div class="card-header" style="background:#FFbA00; color:#0c3b2e; border-radius:18px 18px 0 0;">
                                <h5 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2"></i>Start New Chat</h5>
                            </div>
                            <div class="card-body bg-white">
                                <form method="get" class="mb-3 d-flex">
                                    <input type="text" name="search_parent" class="form-control me-2" placeholder="Search parent..." value="<?php echo isset($_GET['search_parent']) ? htmlspecialchars($_GET['search_parent']) : ''; ?>">
                                    <button type="submit" class="btn" style="background:#6d9773; color:white;">Search</button>
                                </form>
                                <?php if(isset($_GET['search_parent'])): ?>
                                    <div class="list-group">
                                        <?php if($searchResults && mysqli_num_rows($searchResults) > 0): ?>
                                            <?php while($parent = mysqli_fetch_assoc($searchResults)): ?>
                                                <a href="?parent_id=<?php echo $parent['ParentID']; ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                                                    <img src="../Media/default.png" class="rounded-circle me-2" width="35" height="35" alt="Parent">
                                                    <?php echo htmlspecialchars($parent['ParentName']); ?>
                                                </a>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <div class="text-muted p-2">No parents found.</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Chats List -->
                    <div class="col-md-7">
                        <div class="card shadow" style="border-radius:18px;">
                            <div class="card-header" style="background:#0c3b2e; color:#FFbA00; border-radius:18px 18px 0 0;">
                                <h5 class="mb-0 fw-bold"><i class="fas fa-comments me-2"></i>Chats</h5>
                            </div>
                            <div class="chat-list" id="chatList" style="background:#fff; border-radius:0 0 18px 18px;">
                                <?php if(mysqli_num_rows($chats) > 0): ?>
                                    <?php while($chat = mysqli_fetch_assoc($chats)): ?>
                                        <div class="chat-item d-flex align-items-center" onclick="window.location.href='Chat.php?chat_id=<?php echo $chat['ChatID']; ?>'">
                                            <img src="../Media/default.png" class="rounded-circle me-3" width="50" height="50" alt="Parent">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1" style="color:#0c3b2e;">
                                                    <?php echo htmlspecialchars($chat['ParentName']); ?>
                                                    <?php if($chat['unread_count'] > 0): ?>
                                                        <span class="ms-2 text-danger" title="New message">
                                                            <i class="fas fa-bell"></i> New
                                                        </span>
                                                    <?php endif; ?>
                                                </h6>
                                                <div class="last-message">
                                                    <?php echo htmlspecialchars($chat['last_message'] ?? 'No messages yet'); ?>
                                                </div>
                                            </div>
                                            <div class="text-end ms-3">
                                                <?php if($chat['unread_count'] > 0): ?>
                                                    <span class="unread-badge"><?php echo $chat['unread_count']; ?></span>
                                                <?php endif; ?>
                                                <div class="chat-time">
                                                    <?php 
                                                    if($chat['last_message_time']) {
                                                        echo date('M j, g:i a', strtotime($chat['last_message_time']));
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="text-center p-5">
                                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No chats yet</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>