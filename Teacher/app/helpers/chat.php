<?php 

function getChats($id_1, $id_2, $conn){
   
   $sql = "SELECT * FROM chat c join messages m on c.ChatID = m.ChatID
           WHERE (TeacherID=? AND ParentID=?)
           ORDER BY c.ChatID ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_1, $id_2]);

    if ($stmt->rowCount() > 0) {
    	$chats = $stmt->fetchAll();
    	return $chats;
    }else {
    	$chats = [];
    	return $chats;
    }

}