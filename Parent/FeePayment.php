<?php
include "../connection.php";

if(!isset($_SESSION['ParentID'])){
    header("location:login.php");
}

$ParentID = $_SESSION['ParentID'];
$StudentID = $_GET['student'];

// Verify that this student belongs to the logged-in parent
$verifyQuery = "SELECT * FROM family WHERE ParentID = '$ParentID' AND StudentID = '$StudentID'";
$verifyResult = mysqli_query($connect, $verifyQuery);
if(mysqli_num_rows($verifyResult) == 0) {
    header("location:ParentProfile.php");
    exit();
}

// Get student details and fee information
$studentQuery = "SELECT s.*, g.FeeAmount, g.GradeNumber 
                FROM students s 
                JOIN grades g ON s.Grade = g.GradeID 
                WHERE s.StudentID = '$StudentID'";
$studentResult = mysqli_query($connect, $studentQuery);
$student = mysqli_fetch_assoc($studentResult);

// Handle form submission
if(isset($_POST['mark_as_paid'])) {
    // Update student's fee payment status
    $updateStudent = "UPDATE students SET FeePayment = 1 WHERE StudentID = '$StudentID'";
    mysqli_query($connect, $updateStudent);
    
    // Insert payment record
    $amount = $student['FeeAmount'];
    $insertPayment = "INSERT INTO payments (StudentID, fees, TotalPrice) VALUES ('$StudentID', '$amount', '$amount')";
    mysqli_query($connect, $insertPayment);
    
    // Redirect to refresh the page
    header("location:FeePayment.php?student=$StudentID");
    exit();
}

// Get payment history
$paymentQuery = "SELECT * FROM payments WHERE StudentID = '$StudentID'";
$paymentResult = mysqli_query($connect, $paymentQuery);
$payments = [];
while($payment = mysqli_fetch_assoc($paymentResult)) {
    $payments[] = $payment;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fontawesome-free-6.4.0-web/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/fees payment.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title>Fees payment</title>
</head>
<body>


<!-- start navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid ms-4">
            <a class="navbar-brand logo" href="#home">EduVate</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-4">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="ParentProfile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="Subscription.php">Subscription</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Chatting</a>
                    </li>
                </ul>
                <form method="post">
                <button type="submit" name="logout" class="log btn btn-outline-warning ms-3 me-4 ">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <!-- end navbar -->
    

    <div class="container w-50 bg-light">
        <h2>Fees payment</h2><br>
        <h3>school fees: <?php echo number_format($student['FeeAmount'], 2); ?> EGP</h3>
        
        <!-- Status Bar -->
        <div class="status-bar mb-3">
            <div class="d-flex align-items-center">
                <div class="status-indicator <?php echo $student['FeePayment'] == 1 ? 'bg-success' : 'bg-danger'; ?> me-2" style="width: 15px; height: 15px; border-radius: 50%;"></div>
                <span class="status-text fw-bold">
                    <?php echo $student['FeePayment'] == 1 ? 'Payment Status: Paid' : 'Payment Status: Unpaid'; ?>
                </span>
            </div>
        </div>
        <hr>

        <?php if($student['FeePayment'] == 0): ?>
            <h3>Enter your card data:</h3>
            <section class="payment-container" aria-label="Payment Card Information Form">
                <form method="POST" action="" novalidate>
                    <label for="cardholder-name">Cardholder Name</label>
                    <input type="text" id="cardholder-name" name="cardName" placeholder="John Doe" autocomplete="cc-name" required/>

                    <label for="card-number">Card Number</label>
                    <input type="text" id="card-number" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" autocomplete="cc-number" inputmode="numeric" required/>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry-date">Expiration Date</label>
                            <input type="text" id="expiry-date" name="expiry" placeholder="MM / YY" maxlength="7" autocomplete="cc-exp" required/>
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="password" id="cvv" name="cvv" placeholder="123" maxlength="4" autocomplete="cc-csc" required/>
                        </div>
                    </div>
                    <button type="submit" name="mark_as_paid" class="btn btn-warning pay">PAY NOW</button>
                </form>
            </section>
        <?php else: ?>
            <div class="payment-history">
                <h3>Payment History</h3>
                <?php if(count($payments) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($payments as $payment): ?>
                                    <tr>
                                        <td><?php echo number_format($payment['Fees'], 2); ?> EGP</td>
                                        <td><?php echo number_format($payment['TotalPrice'], 2); ?> EGP</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No payment history available.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 
