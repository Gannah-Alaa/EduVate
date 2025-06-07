<?php
include("../connection.php");
if(isset($_GET['BusId'])){
  $BusId=$_GET['BusId'];
  
  $selectBus = "SELECT * FROM bus WHERE BusID = $BusId";
  $runSelectBus = mysqli_query($connect, $selectBus);


}else{
    header("location:AllBuses.php");
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
    <?php if(mysqli_num_rows($runSelectBus) > 0){
        foreach($runSelectBus as $Bus){?>
<div class="card">
<?php echo $Bus['BusNumber']?>
<?php echo $Bus['Destination']?>
<?php echo $Bus['BusSupervisor']?>
<?php echo $Bus['SupervisorNumber']?>
</div>
<a href="AllBuses.php">Back</a>
<?php if(isset($_SESSION['StudentID'])){
print("<a href='Payment.php?BusId=" . $_GET['BusId'] . "'>Pay</a>");
}else{
    // print("<a href='Payment.php?BusId=" . $_GET['BusId'] . "'>Pay</a>");

    print("<a href='login.php'>Pay</a>");

}


}
}else{
    echo "No Buss";
} ?>
</body>
</html>
