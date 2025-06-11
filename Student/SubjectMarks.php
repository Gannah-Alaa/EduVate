<?php

include("../connection.php");
//static $StudentID=1; and invert cond of isset session
if(isset($_SESSION['StudentID'])){
$StudentID=$_SESSION['StudentID'];
if(isset($_GET['SubjectId']) && isset($_GET['GradeID'])){
    $SubjectId = $_GET['SubjectId'];

$selectMarks="SELECT * FROM marks M 
join subjects S on S.SubjectID=M.SubjectID
 WHERE StudentID = $StudentID AND M.SubjectID = $SubjectId
  ORDER by MarkType";
$runSelectMarks = mysqli_query($connect, $selectMarks);


}else{
    header("location:AllSubjects.php");
}
}else{
    header("location:login.php");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php foreach($runSelectMarks as $Mark){
        if($Mark['MarkType'] == "Quiz"){
            echo $Mark['MarkType'];
            echo $Mark['Mark'];
            echo "<br>";
        }else if($Mark["MarkType"] == "Attendance"){
        echo $Mark['SubjectName'];
        echo $Mark['MarkType'];
        echo "<br>";
        }else if($Mark["MarkType"] == "Finals"){
            echo $Mark['SubjectName'];
            echo $Mark['MarkType'];
            echo "<br>";
        }
    } ?>
</body>
</html>



