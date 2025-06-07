<?php

include("../connection.php");

$AllBuses = "SELECT * FROM bus";

$runAllBuses = mysqli_query($connect, $AllBuses);





?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <?php if(mysqli_num_rows($runAllBuses) > 0) {
        foreach($runAllBuses as $Bus){?>
        
    <div class="Card">

    <a href="BusDetails.php?BusId=<?php echo $Bus['BusID'] ?>">More Info</a>

    <p><?= $Bus['BusNumber']?></p>
    <p><?= $Bus['Destination']?></p>

    </div>

    <?php }}else{
        echo "No Buses";
    } ?>
</body>
</html>


