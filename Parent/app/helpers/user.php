<?php  

function getUser($ParentId, $conn){
   $sql = "SELECT * FROM teachers 
           WHERE TeacherID=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$ParentId]);

   if ($stmt->rowCount() === 1) {
   	 $user = $stmt->fetch();
   	 return $user;
   }else {
   	$user = [];
   	return $user;
   }
}