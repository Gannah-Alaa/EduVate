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
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Edit your profile</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone number:</label>
                                <input name="phone" id="phone" type="text" class="form-control" value="<?php echo $Phone; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address:</label>
                                <input name="address" id="address" type="text" class="form-control" value="<?php echo $address; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image:</label>
                                <input name="image" type="file" class="form-control" id="image" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" name="update" class="btn btn-warning mt-2">DONE <i class="fa-solid fa-right"></i></button>
                                <a href="profile.php" class="btn btn-secondary">Back to Profile</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src=""></script>
</body>
</html>