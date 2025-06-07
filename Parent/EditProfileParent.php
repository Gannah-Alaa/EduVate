<?php
include "../connection.php";

if(!isset($_SESSION['ParentID'])){
    header("location:login.php");
}

$ParentID = $_SESSION['ParentID'];
$error = "";
$success = "";

// Fetch parent information
$select = "SELECT * FROM `parents` WHERE `ParentID` = '$ParentID'";
$runSelect = mysqli_query($connect, $select);
$parent = mysqli_fetch_assoc($runSelect);

if(isset($_POST['submit'])){
    // Escape user input to prevent SQL errors and SQL injection
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $number = mysqli_real_escape_string($connect, $_POST['number']);
    
    $update = "UPDATE `parents` SET `ParentName` = '$name', `ParentNumber` = '$number' WHERE `ParentID` = '$ParentID'";
    $runUpdate = mysqli_query($connect, $update);
    
    if($runUpdate){
        $success = "Profile updated successfully";
        // Refresh parent data
        $select = "SELECT * FROM `parents` WHERE `ParentID` = '$ParentID'";
        $runSelect = mysqli_query($connect, $select);
        $parent = mysqli_fetch_assoc($runSelect);
    } else {
        $error = "Something went wrong";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fontawesome-free-6.4.0-web/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/edit profile.css">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <title>Edit Profile</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Edit Profile</h3>
                    </div>
                    <div class="card-body">
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $parent['ParentName']; ?>" required>
                            </div>
                        
                            
                            <div class="mb-3">
                                <label for="number" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="number" name="number" value="<?php echo $parent['ParentNumber']; ?>" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                <a href="ParentProfile.php" class="btn btn-secondary">Back to Profile</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
