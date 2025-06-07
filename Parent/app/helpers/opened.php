<?php 

function opened($id_1, $conn, $chats){
    foreach ($chats as $chat) {
    	if ($chat['Seen'] == "Unseen") {
    		$opened = 1;
    		$chat_id = $chat['chat_id'];

    		$sql = "UPDATE chat
    		        SET   seen = ?
    		        WHERE TeacherId=? 
    		        AND   ChatID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$opened, $id_1, $chat_id]);

    	}
    }
}