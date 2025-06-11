<?php
include '../connection.php';


$error="";
$err=FALSE;
$done=FALSE;

$StudentID=$_SESSION['StudentID'];

if(isset($_POST['submit'])){
    // echo "entered the scope";
    $select="SELECT * FROM `students` WHERE `StudentID`='$StudentID'";
    $run=mysqli_query($connect,$select);

    $fetch=mysqli_fetch_assoc($run);
    $fetchold=$fetch['StudentPass'];

    $old_pass=$_POST['old_password'];
    $new_pass=$_POST['new_password'];
    $confirm_pass=$_POST['confirm_password'];

    $lowercase=preg_match('@[a-z]@',$new_pass);
    $uppercase=preg_match('@[A-Z]@',$new_pass);
    $numbers=preg_match('@[0-9]@',$new_pass);
    $character=preg_match('@[^\w]@', $new_pass);


    if(password_verify($old_pass,$fetchold)){
        // echo "entered the scope1";
        if($new_pass==$confirm_pass){

            if (strlen($new_pass) < 6) {
                $error = "The password should have at least 6 characters.";
                $err = TRUE;
            }elseif ($lowercase <1 || $uppercase<1 || $numbers<1 || $character<1 ){
                $error= "password must contain uppercase, lowercase, number and character ";
                $err = TRUE;
            }else{
            $new_hashing= password_hash($new_pass, PASSWORD_DEFAULT);
            // echo "gamed";
            $update="UPDATE `students` SET `StudentPass`='$new_hashing' WHERE `StudentID`='$StudentID'";
            $ruunupdate=mysqli_query($connect,$update);
       
            echo "Password Changed Successfully";;
            header("location:Profile.php");
            }

        }else{
            $error= "The new password doesn't match the confirmed one";
            $err=TRUE;
        }
    }else{
        $error= "Old password is incorrect";
        $err=TRUE;
        // echo "errrr";
    }

}   


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reset password</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./cssf/all.min.css">
    <link rel="stylesheet" href="./css/resetpass.css">
    <script src="./js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100 ">
        <div class="card" id="passwordChangeCard">
           <div class="d-flex justify-content-center mb-2"><img src="./imgs/pass.png" alt="Key Icon" class="key-icon"></div> 
        
            <h2 class="text-center fw-bold">Reset password</h2>
            <p class="text-center mt-2 mb-4">Enter a new password below to change your password</p>


            <!-- <form id="changePasswordForm" method="POST"> -->
            <form method="POST">
                <div class="form-group mb-3">
                    <label for="oldPassword">Old password</label>
                    <input type="password" name="old_password"  class="form-control" id="oldPassword"  required>
                </div>
                <div class="form-group mb-4">
                    <label for="newPassword">New password</label>
                    <input type="password" name="new_password" class="form-control" id="newPassword"  required>
                </div>
                <div class="form-group mb-4">
                    <label for="confirmnewPassword">Confirm New password</label>
                    <input type="password" name="confirm_password" class="form-control" id="confirmnewPassword"  required>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="submit" class="btn btn-primary">Reset Password</button>
                    <a href="Profile.php" class="btn btn-secondary">Back to Profile</a>
                </div>
                <!-- <div id="successMessage" style="display: none; background-color: #d4edda; color: #155724; padding: 15px; border: 2px solid #c3e6cb; border-radius: 5px; margin-top: 20px;">
                    Your password has been changed successfully. You can now log in with your new password.
                </div> -->

                 <!-- -----------------------error------------------------------- -->
                    <?php if($err){ ?> 
                        <div  style="background-color: #d4edda; color:rgb(158, 26, 22); padding: 15px; border: 2px solid #c3e6cb; border-radius: 5px; margin-top: 20px;">
                        <?php echo $error ;  ?></div>
                        <?php  }elseif($done){ ?>
                            <div  style="background-color: #d4edda; color: #155724; padding: 15px; border: 2px solid #c3e6cb; border-radius: 5px; margin-top: 20px;">
                    Your password has been changed successfully. You can now log in with your new password.
                </div>
                        <?php } ?>
                        
                    
                    <!-- -------------------------error------------------------------ -->
                <!-- <div id="successMessage" style="display: none; background-color: #d4edda; color:rgb(158, 26, 22); padding: 15px; border: 2px solid #c3e6cb; border-radius: 5px; margin-top: 20px;">
                    Your password has been changed successfully. You can now log in with your new password. -->
                </div>
            </form>
        </div>
    </div>
    





    <!-- <script src="./js/resetpass.js"></script> -->
</body>
</html>