<?php
include '../connection.php';

$studentId=1;
//$studentId= $_SESSION['StudentID'];

    $selectQuery="SELECT * FROM marks M join subjects S on M.SubjectID=S.SubjectID where StudentID=$studentId order by M.SubjectID";
    $RunQuery=mysqli_query($connect,$selectQuery);










?>



<!DOCTYPE html>   
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if(mysqli_num_rows($RunQuery)>0){
foreach ($RunQuery as $data) {?>

    <?php echo $data['SubjectName'].":    ";
     if($data['MarkType']=='Quiz' || $data['MarkType']=='Assignment'){echo $data['MarkType'].":". $data['MarkValue']."/10";}
    elseif($data['MarkType']=='Participation'){echo  $data['MarkType'].":". $data['MarkValue']."/5";}
    elseif($data['MarkType']=='Mid'){echo  $data['MarkType'].":". $data['MarkValue']."/20";}
    elseif($data['MarkType']=='Final'){echo  $data['MarkType'].":". $data['MarkValue']."/50";}
    
    ?></p>
<?php }}?>
</body>
</html>