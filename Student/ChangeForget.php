<?php
include '../connection.php';





$error="";
$err=FALSE;
if(isset($_POST['submit'])){
     $email=$_SESSION['email'];
     $password=$_POST['pass'];
     $confirm_password=$_POST['confirm_password'];

     $hashed=password_hash($password,PASSWORD_DEFAULT);

     $lowercase=preg_match('@[a-z]@',$password);
     $uppercase=preg_match('@[A-Z]@',$password);
     $numbers=preg_match('@[0-9]@',$password);
     $character=preg_match('@[^\w]@', $password);

     if (strlen($password) < 6) {
        $error = "The password should have at least 6 characters.";
        $err = TRUE;
    }elseif ($password !=$confirm_password){
        $error="password doesn't match confirm password";
        $err = TRUE;
    }elseif ($uppercase<1 ||$lowercase<1 ||$numbers<1 ||$character<1 ){
        $error= "password must contain upeercase, lowercase, number and character ";
        $err = TRUE;
    }else{
     $update="UPDATE `students` SET `StudentPass`='$hashed' WHERE `StudentEmail`='$email'";
     $ruunupdate=mysqli_query($connect,$update);

     echo "Password Changed Successfully";

     unset($_SESSION['otp']);
     unset($_SESSION['email']);
     header("location:login.php");
}}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ----------------------------------bootstrap link------------------------------------- -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- --------------------------------google font--------------------------- -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Rubik:ital,wght@0,300..900;1,300..900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- ---------------------------font awesome-------------------------------- -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- -----------------------------css link------------------------------------- -->
    <link rel="stylesheet" href="./CSS/changepass.css">
    <title>Document</title>
</head>

<body>
    <!-- ---------------------------error area---------------------------- -->

    <!-- ------------------------------error area end-------------------- -->
    <div class="home-section">






        <div class="main-form">
            <div class="sub-form">

                <h1>Change Password</h1>

                <form action="" method="POST" class="mini-form">


                    <!-- old pass -->
                    <div class="eye">
                        <i class="fa-solid fa-eye" style="color: white;"></i>
                    </div>

                    <!-- <input type="password" id="old" placeholder="Enter Your Old Password . ." class="pass"><br> -->



                    <!-- new pass -->
                    <div class="eye">
                        <i class="fa-solid fa-eye" style="color: white;"></i>
                    </div>


                    <input type="password" id="new" name="pass" placeholder="Enter Your New Password" class="pass"><br>

                    <!-- confirm new pass -->

                    <div class="eye ">
                        <i class="fa-solid fa-eye" style="color: white;"></i>
                    </div>


                    <input type="password"name="confirm_password" id="cnew" placeholder="Confirm New . ." class="pass"><br>

                    <!-- -----------------------error------------------------------- -->
                    <div class="error">
                    <?php if ($err){ ?>
                                            <p>error</p>
                                            <?php echo $error; } ?>
                    </div>
                    <!-- -------------------------error------------------------------ -->
                    <div class="bttn">

                        <button type="submit"name= "submit"class="Change-btn">Change</button>


                    </div>





                </form>
            </div>
        </div>


        <div class="pic">
            <img src="./images/Reset password-cuate (1).png" height="500px" width="500px">
        </div>



    </div>

    <!-- ----------------------------------------bootstrap link---------------------------------- -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

<script src="./js/changepass.js"></script>


</html>
