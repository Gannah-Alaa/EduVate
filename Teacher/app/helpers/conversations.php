<?php 

function getConversation($user_id, $conn){
    /**
      Getting all the conversations 
      for current (logged in) user
    **/
    $sql = "SELECT * FROM chat
            WHERE TeacherID=? 
            ORDER BY ChatID DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);

    if($stmt->rowCount() > 0){
        $conversations = $stmt->fetchAll();

        /**
          creating empty array to 
          store the user conversation
        **/
        $user_data = [];
        
        # looping through the conversations
        foreach($conversations as $conversation){
            # if conversations user_1 row equal to user_id
            if ($conversation['TeacherID'] == $user_id) {
            	$sql2  = "SELECT *
            	          FROM parents P join chat c on P.ParentID=c.ParentID WHERE P.ParentID=?";
            	$stmt2 = $conn->prepare($sql2);
            	$stmt2->execute([$conversation['ParentID']]);
            }
            // else {
            //   $sql2  = "SELECT *
            // 	          FROM parents WHERE ParentID=?";
            // 	$stmt2 = $conn->prepare($sql2);
            // 	$stmt2->execute([$conversation['ParentID']]);
            // }

            $allConversations = $stmt2->fetchAll();

            # pushing the data into the array 
            array_push($user_data, $allConversations[0]);
        }

        return $user_data;

    }else {
    	$conversations = [];
    	return $conversations;
    }  

}