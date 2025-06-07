<?php  

function getParent($ParentId, $conn){
   $sql = "SELECT * FROM parents 
           WHERE ParentID=?";
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