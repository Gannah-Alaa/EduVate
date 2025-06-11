<?php
include "../connection.php";

if(!isset($_SESSION['ParentID'])){
    header("location:login.php");
}

$ParentID = $_SESSION['ParentID'];

// Fetch subscription status
$parentQuery = mysqli_query($connect, "SELECT is_subscribed FROM parents WHERE ParentID = '$ParentID'");
$parent = mysqli_fetch_assoc($parentQuery);
$isSubscribed = $parent['is_subscribed'];

if(isset($_POST['subscribe'])){
    $UPDATE = "UPDATE `parents` SET `is_subscribed` = '1' WHERE `ParentID` = '$ParentID'";
    $run = mysqli_query($connect, $UPDATE);
    if($run){
        header("location:ParentProfile.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../fontawesome-free-6.4.0-web/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/subscription.css">
    <title>Subscription Plans</title>
</head>
<body>
    <!-- start navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid ms-4">
            <a class="navbar-brand logo" href="home.php"><img src="../Media/logo.png" width="250px" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-4">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#aboutus-container">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#events">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#contactus">Contact Us</a>
                    </li>
                    <?php if(isset($_SESSION['ParentID'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="ParentProfile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="Subscription.php">Subscription</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="ParentChats.php">Chatting</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php if(isset($_SESSION['ParentID'])): ?>
                    <form method="post">
                        <button type="submit" name="logout" class="btn btn-outline-warning ms-3 me-4 ">Logout</button>
                    </form>
                <?php else: ?>
                    <button type="button" class="btn btn-outline-warning ms-3 me-4 "><a href="login.php">Login</a></button>
                <?php endif; ?>
            </div>
        </div>
    </nav>  <br><br> <br><br> <br>
    <!-- end navbar -->

    <div class="container subscription-container">
        <div class="subscription-wrapper">
            <div class="subscription-content">
                <!-- Basic Plan -->
                <div class="card pricing-card mb-4">
                    <div class="card-header">
                        <h3 class="text-center">Premium</h3>
                    </div>
                    <div class="card-body">
                        <h2 class="card-title pricing-card-title text-center">999 EGP<span class="text-muted">/mo</span></h2>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li><i class="fas fa-check text-success"></i> Chat system with teachers</li>
                            <li><i class="fas fa-check text-success"></i> Grade Charts</li>
                        </ul>
                    </div>
                </div>

                <?php if($isSubscribed == 1): ?>
                    <div class="alert alert-success text-center">
                        You are already subscribed to the Premium Plan!
                    </div>
                <?php else: ?>
                <!-- Payment Form -->
                <div class="payment-form" id="paymentForm" method="POST">
                    <div class="card">
                        <div class="card-header">
                            <h3>Payment Details</h3>
                            <p id="selectedPlan" class="mb-0">Selected Plan: Basic - $9.99/month</p>
                        </div>
                        <div class="card-body">
                            <form id="subscriptionForm" method="POST">
                                <div class="mb-3">
                                    <label for="cardName" class="form-label">Cardholder Name</label>
                                    <input type="text" class="form-control" id="cardName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cardNumber" class="form-label">Card Number</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="cardNumber" required maxlength="19" placeholder="1234 5678 9012 3456">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="expiryDate" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control" id="expiryDate" required placeholder="MM/YY">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="password" class="form-control" id="cvv" required maxlength="4" placeholder="123">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-warning w-100" name="subscribe">Subscribe Now</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // Format card number with spaces
        document.getElementById('cardNumber')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        // Format expiry date
        document.getElementById('expiryDate')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            if (value.length > 2) {
                value = value.slice(0, 2) + '/' + value.slice(2);
            }
            e.target.value = value;
        });
    </script>
</body>
</html>
