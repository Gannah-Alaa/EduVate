<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.conn.php';

// Function to check if parent is subscribed
function isParentSubscribed($parentId, $conn) {
    $sql = "SELECT Is_subscribed FROM parents WHERE ParentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $parentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['Is_subscribed'] == 1;
}

// Function to create or get existing chat
function getOrCreateChat($teacherId, $parentId, $conn) {
    // Check if chat exists
    $sql = "SELECT ChatID FROM chat WHERE TeacherID = ? AND ParentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $teacherId, $parentId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['ChatID'];
    }
    
    // Create new chat
    $sql = "INSERT INTO chat (TeacherID, ParentID) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $teacherId, $parentId);
    $stmt->execute();
    return $conn->insert_id;
}

// Function to send message
function sendMessage($chatId, $text, $media, $sender, $conn) {
    $sql = "INSERT INTO messages (ChatID, Text, Media, Sender, DateTime) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $chatId, $text, $media, $sender);
    return $stmt->execute();
}

// Function to get messages
function getMessages($chatId, $conn) {
    $sql = "SELECT * FROM messages WHERE ChatID = ? AND IsDeleted = 'False' ORDER BY DateTime ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $chatId);
    $stmt->execute();
    return $stmt->get_result();
}

// Function to mark messages as seen
function markMessagesAsSeen($chatId, $conn) {
    $sql = "UPDATE messages SET Seen = 'Seen' WHERE ChatID = ? AND Seen = 'Unseen'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $chatId);
    return $stmt->execute();
}

// Function to get unread message count
function getUnreadCount($chatId, $conn) {
    $sql = "SELECT COUNT(*) as count FROM messages WHERE ChatID = ? AND Seen = 'Unseen'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $chatId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'];
}

function getParentChats($parentId, $conn) {
    $sql = "SELECT c.ChatID, t.TeacherName, t.TeacherPic,
                   (SELECT Text FROM messages m WHERE m.ChatID = c.ChatID ORDER BY m.DateTime DESC LIMIT 1) AS LastMessage,
                   (SELECT DateTime FROM messages m WHERE m.ChatID = c.ChatID ORDER BY m.DateTime DESC LIMIT 1) AS LastMessageTime
            FROM chat c
            JOIN teachers t ON c.TeacherID = t.TeacherID
            WHERE c.ParentID = ?
            ORDER BY LastMessageTime DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $parentId);
    $stmt->execute();
    return $stmt->get_result();
}
?> 