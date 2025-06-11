<?php
include "../connection.php";

if(!isset($_SESSION['ParentID'])){
    header("location:login.php");
}

$ParentID = $_SESSION['ParentID'];
$error = "";
$success = "";

if(isset($_POST['submit'])){
    $currentPass = $_POST['current'];
    $newPass = $_POST['new'];
    $confirmPass = $_POST['confirm'];
    
    // Get current password
    $select = "SELECT `ParentPass` FROM `parents` WHERE `ParentID` = '$ParentID'";
    $runSelect = mysqli_query($connect, $select);
    $parent = mysqli_fetch_assoc($runSelect);
    
    if(password_verify($currentPass, $parent['ParentPass'])){
        if($newPass === $confirmPass){
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            $update = "UPDATE `parents` SET `ParentPass` = '$hash' WHERE `ParentID` = '$ParentID'";
            $runUpdate = mysqli_query($connect, $update);
            
            if($runUpdate){
                $success = "Password updated successfully";
            } else {
                $error = "Something went wrong";
            }
        } else {
            $error = "New passwords do not match";
        }
    } else {
        $error = "Current password is incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./cssf/all.min.css">
    <link rel="stylesheet" href="./css/resetpass.css">
    <script src="./js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-8">

            <div class="container d-flex justify-content-center align-items-center vh-80 ">
        <div class="card" id="passwordChangeCard">
           <div class="d-flex justify-content-center"><img src="./imgs/pass.png" alt="Key Icon" class="key-icon"></div> 
        
            <h2 class="text-center fw-bold">Reset password</h2>
            <p class="text-center">Enter a new password below to change your password</p>

                    <div class="card-body">
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="current" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current" name="current" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="new" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new" name="new" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm" name="confirm" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" name="submit" class="btn btn-primary">Reset Password</button>
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
