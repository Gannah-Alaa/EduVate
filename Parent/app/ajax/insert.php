<?php 

session_start();

# check if the user is logged in
if (isset($_SESSION['TeacherID'])) {

	if (isset($_POST['message']) &&
        isset($_POST['to_id'])) {
	
	# database connection file
	include '../db.conn.php';

	# get data from XHR request and store them in var
	$message = $_POST['message'];
	$to_id = $_POST['to_id'];
	$ChatID= $_POST['ChatID'];
	# get the logged in user's username from the SESSION
	$from_id = $_SESSION['TeacherID'];

	$file_url = "";

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);

        if (in_array(strtolower($ext), $allowed)) {
            $new_name = uniqid() . '.' . $ext;
            $destination = "../../uploads/" . $new_name;

            if (move_uploaded_file($file_tmp, $destination)) {
                $file_url = $new_name;
            }
        }
    }

	$sql = "INSERT INTO 
	       messages  
	       VALUES (?,?, ?, ?,?,?,?,?)";
	$stmt = $conn->prepare($sql);
	$res  = $stmt->execute([null,$ChatID, $message,$file_url,0,date("Y-m-d H:i:s"),$from_id,"Unseen"]);
    
    # if the message inserted
    if ($res) {
    	/**
       check if this is the first
       conversation between them
       **/
       $sql2 = "SELECT * FROM messages
               WHERE ChatID=?
			   ";
       $stmt2 = $conn->prepare($sql2);
	   $stmt2->execute([$ChatID]);

	    // setting up the time Zone
		// It Depends on your location or your P.c settings
		define('TIMEZONE', 'Africa/Addis_Ababa');
		date_default_timezone_set(TIMEZONE);

		$time = date("h:i:s a");

		if ($stmt2->rowCount() == 0 ) {
			# insert them into conversations table 
			$sql3 = "INSERT INTO 
			         chat
			         VALUES (?,?,?)";
			$stmt3 = $conn->prepare($sql3); 
			$stmt3->execute([null,$from_id, $to_id]);
		}
		?>

		<p class="rtext align-self-end
		          border rounded p-2 mb-1">
		    <?=$message?>  
		    <small class="d-block"><?=$time?></small>      	
		</p>

    <?php 
     }
  }
}else {
	header("Location: ../../index.php");
	exit;
}