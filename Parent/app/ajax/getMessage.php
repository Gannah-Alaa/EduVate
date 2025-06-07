<?php 

session_start();

# check if the user is logged in
if (isset($_SESSION['username'])) {

	if (isset($_POST['id_2'])) {
	
	# database connection file
	include '../db.conn.php';

	$TeacherId  = $_SESSION['TeacherID'];
	$ParentId  = $_POST['ParentID'];
	$opened = 0;

	$sql = "SELECT * FROM chat
	        WHERE TeacherID=?
	        AND   ParentID= ?
	        ORDER BY chat_id ASC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$TeacherId, $ParentId]);

	if ($stmt->rowCount() > 0) {
	    $chats = $stmt->fetchAll();

	    # looping through the chats
	    foreach ($chats as $chat) {
	    	if ($chat['opened'] == 0) {
	    		
	    		$opened = 1;
	    		$chat_id = $chat['chat_id'];

	    		$sql2 = "UPDATE chats
	    		         SET opened = ?
	    		         WHERE chat_id = ?";
	    		$stmt2 = $conn->prepare($sql2);
	            $stmt2->execute([$opened, $chat_id]); 

	            ?>
                  <p class="ltext border 
					        rounded p-2 mb-1">
					    <?=$chat['message']?> 
					    <small class="d-block">
					    	<?=$chat['created_at']?>
					    </small>      	
				  </p>        
	            <?php
	    	}
	    }
	}

 }

}else {
	header("Location: ../../index.php");
	exit;
}