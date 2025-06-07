<?php 

function lastChat($TeacherID, $ParentID, $conn){
   
   $sql = "SELECT * FROM chat c join messages m on c.ChatID = m.ChatID
           WHERE (TeacherID=? AND ParentID=?)
           ORDER BY c.ChatID DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$TeacherID, $ParentID]);

    if ($stmt->rowCount() > 0) {
    	$chat = $stmt->fetch();
    	return $chat['Text'];
    }else {
    	$chat = '';
    	return $chat;
    }

}