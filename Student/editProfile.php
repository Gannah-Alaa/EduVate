<?php
include "../connection.php";

$StudentID=$_SESSION['StudentID'];
if(isset($_GET['edit'])){
    $select="SELECT * FROM `students` WHERE `StudentId` = $StudentID";
    $runselect=mysqli_query($connect,$select);
    $array=mysqli_fetch_assoc($runselect);

    $Phone=$array['StudentNumber'];
    $address=$array['StudentAddress'];
    $email=$array['StudentEmail'];
    $Image=$array['Picture'];




if(isset($_POST['update'])){
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $image = $Image;
    
    // Only update image if a new one was uploaded
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = $_FILES['image']['name'];
        // Generate unique filename to prevent conflicts
        $image = uniqid() . '_' . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], "../Media/" . $image);
        
        // Optionally delete old image file if needed
        if ($currentImage && file_exists("../Media/" . $currentImage)) {
            unlink("../Media/" . $currentImage);
        }
    }
    
    $Update = "UPDATE `students` SET `StudentNumber`='$phone', `StudentAddress`='$address', `Picture`='$image' WHERE `StudentID`=$StudentID";
    $runUpdate = mysqli_query($connect, $Update);
    
        Header("Location: profile.php");
}}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fontawesome-free-6.4.0-web/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="./CSS/edit profile.css">
    <title>Edit profile</title>
</head>
<body class="bg-light">

    <div class="container d-flex align-items-center justify-content-center">
    <div class="editp shadow m-auto p-4">
        <h3 class="text-center">Edit your profile</h3>
        <div class="inputs m-auto">

            <form action="" method="post" enctype="multipart/form-data">
            <!-- <label for="name">Name:</label>
            <input id="name" type="text" class="form-control"> -->
            <label for="name">Phone number:</label>
            <input name="phone" id="name" type="text" class="form-control" value="<?php echo $Phone; ?>">
            <!-- <label for="name">Email:</label>
            <input name="email"id="name" type="email" class="form-control" value="<?php echo $email; ?>"> -->
            <label for="name">Address:</label>
            <input name="address" id="name" type="text" class="form-control" value="<?php echo $address; ?>">
            <label for="name">Image:</label>
            <input name="image" type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
            <div class="submit">
                <button type="submit" name="update" class="btn btn-warning mt-4">DONE <i class="fa-solid fa-right"></i></button>
            </div>
            </form>

        </div>
    </div>
    </div>




    <script src=""></script>
</body>
</html>