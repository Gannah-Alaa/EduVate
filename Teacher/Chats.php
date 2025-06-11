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
     <link rel="stylesheet" href="./CSS/bootstrap.min.css">
    <link rel="stylesheet" href="./CSS/all.min.css">
    <link rel="stylesheet" href="../fontawesome-free-6.4.0-web/css/all.min.css">

    <title>Teacher Chats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
    top: 0  ;
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
        }
        .card-body form{
            display: flex;
            flex-direction: column;
        }

        .search-btn{
            font-weight: 600;
            margin-top: 15px;
        }
        .main-center-row {
            width: 80vw;
            min-height: 75vh;
            /* margin: 0 auto; */
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
            /* box-shadow: none; */
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
        }

        .card {
            border: none;
        }

        
        .chat-list {
            /* min-height: 70vh;
            width: 90%; */
            overflow-y: auto;
            padding: 0 10px;
            position: relative;
            top: 0;
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
<body class="bg-light">


<!-- start navbar -->

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid ms-4">
            <a class="navbar-brand logo" href="home.php"><img src="../Media/logo.png" width="250px" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-4">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#aboutus-container">AboutUs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#events">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#contactus">ContactUs</a>
                    </li>
                    <?php if(isset($_SESSION['TeacherID'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="TeacherProfile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="Chats.php">Chats</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="TeacherProfile.php#classes">Calsses</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php if(isset($_SESSION['TeacherID'])): ?>
                    <form method="post">
                        <button type="submit" name="logout" class="btn btn-outline-warning ms-3 me-4 ">Logout</button>
                    </form>
                <?php else: ?>
                    <button type="button" class="btn btn-outline-warning ms-3 me-4 "><a href="login.php">Login</a></button>
                <?php endif; ?>
            </div>
        </div>
    </nav>  
    <!-- end navbar -->


    <div class="container-fluid py-5">
        <div class="main-center-row mx-auto">
            <div class="col-md-5">
                <div class="card " style="border-radius:0; height:100%;">
                    <div class="card-header" style="background:#FFbA00; color:#0c3b2e; border-radius:0;">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2"></i>Start New Chat</h5>
                    </div>
                    <div class="card-body bg-white">
                        <form method="get" class="mb-3 d-flex">
                            <input type="text" name="search_parent" class="form-control me-2" placeholder="Search parent..." value="<?php echo isset($_GET['search_parent']) ? htmlspecialchars($_GET['search_parent']) : ''; ?>">
                            <button type="submit" class="btn btn-outline-warning search-btn">Search</button>
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
            <div class="col-md-7">
                <div class="card ">
                    <div class="card-header" style="background:#0c3b2e; color:#FFbA00; border-radius:0;">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-comments me-2"></i>Chats</h5>
                    </div>
                    <div class="chat-list" id="chatList" style="background:#fff; border-radius:0;">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>