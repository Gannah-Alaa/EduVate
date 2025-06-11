<?php
include "../connection.php";

if(!isset($_SESSION['ParentID'])){
    header("location:login.php");
}

$ParentID = $_SESSION['ParentID'];

// Fetch parent information
$select = "SELECT * FROM `parents` WHERE `ParentID` = '$ParentID'";
$runSelect = mysqli_query($connect, $select);
$parent = mysqli_fetch_assoc($runSelect);

// Fetch children information
$selectChildren = "SELECT s.*, g.GradeNumber, c.ClassName 
    FROM `students` s 
    JOIN `family` f ON s.StudentID = f.StudentID 
    JOIN `grades` g ON s.Grade = g.GradeID 
    JOIN `class` c ON s.Class = c.ClassID 
    WHERE f.ParentID = '$ParentID'";
$runSelectChildren = mysqli_query($connect, $selectChildren);
$children = [];
while($child = mysqli_fetch_assoc($runSelectChildren)){
    $children[] = $child;
}

$numChildren = count($children);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Profile</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./cssf/all.min.css">
    <link rel="stylesheet" href="./css/profileparent.css">
    <script src="./js/bootstrap.bundle.min.js"></script>
</head>
<body>


<!-- start navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid ms-4">
            <a class="navbar-brand logo" href="#home"><img src="../Media/logo.png" width="250px" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-4">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#aboutus-container">AbouUs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#events">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php  #contactus">ContactUs</a>
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
    </nav>  <br><br><br><br><br> <br>
    <!-- end navbar -->



    <header>
        <div class="container">
            <div class="above row bg-body-secondary">
                <div class="lift col-5 p-3">
                    <div class="d-flex justify-content-evenly row">
                        <div class="d-flex justify-content-center">
                            <img src="./imgs/parent.png" alt="profile page" class="rounded-circle d-flex h-auto" height="100" width="100">
                        </div>
                        <div class="text-center">
                            <h3 class="mt-2"><?php echo $parent['ParentName']; ?></h3>
                            <p class="mb-2"><?php echo $parent['ParentNumber']; ?></p>
                        </div>
                        <div class="d-flex justify-content-center">
                            <a href="EditProfileParent.php" class="btn bg-purple-200 p-2 rounded-full border me-4">
                                <i class="fa-solid fa-user-pen"></i>
                            </a>
                            <a href="ResetPassParent.php" class="btn bg-purple p-2 rounded-full border">
                                <i class="fa-solid fa-key"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="right col-7 p-3">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h2 class="text-lg font-semibold border-bottom pb-2 mb-4">Parent Information</h2>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-envelope me-2 text-purple-600"></i>
                                        <div>
                                            <span class="text-gray-700 d-block small">Email Address</span>
                                            <p class="text-purple-600 mb-0"><?php echo $parent['ParentEmail']; ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-credit-card me-2 text-purple-600"></i>
                                        <div>
                                            <span class="text-gray-700 d-block small">Subscription Plan</span>
                                            <p class="text-purple-600 mb-0"><?php if($parent['Is_subscribed'] == 1){echo "Premium";}else{echo "Free";} ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-children me-2 text-purple-600"></i>
                                        <div>
                                            <span class="text-gray-700 d-block small">Number of Kids</span>
                                            <p class="text-purple-600 mb-0"><?php echo $numChildren; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="container mt-5">
        <div class="max-w-4xl mx-auto bg-white">
            <nav class="d-flex border-bottom border-gray-300 text-sm sm:text-base">
                <?php foreach($children as $index => $child): ?>
                <a id="tab-kid<?php echo $index + 1; ?>" class="py-3 px-4 <?php echo $index === 0 ? 'active' : ''; ?> text-dark text-decoration-none">
                    <?php echo $child['StudentName']; ?> (<?php echo $child['GradeNumber']; ?>)
                </a>
                <?php endforeach; ?>
            </nav>

            <?php foreach($children as $index => $child): ?>
            <section id="kid<?php echo $index + 1; ?>" class="p-6 sm:p-10 <?php echo $index === 0 ? '' : 'd-none'; ?> border-top border-gray-300 min-vh-25">
                <div class="student-card mb-4">
                    <div class="row bg-body-tertiary rounded-3 p-3">
                        <div class="col-md-3 text-center">
                            <img src="<?php echo $child['Picture'] ? '../Media/' . $child['Picture'] : './imgs/profile.jpg'; ?>" 
                                 alt="Student" class="rounded-circle mb-3" width="100" height="100">
                            <h4 class="text-purple-600"><?php echo $child['StudentName']; ?></h4>
                            <p class="text-gray-700">ID: <?php echo $child['StudentID']; ?></p>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong></strong> <span class="text-purple-600"><?php echo $child['GradeNumber']; ?></span></p>
                                    <p><strong>Class:</strong> <span class="text-purple-600"><?php echo $child['ClassName']; ?></span></p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="ChildProfile.php?student=<?php echo $child['StudentID']; ?>" class="btn bg-purple me-2">View Schedule</a>
                                <a href="FinalReport.php?student=<?php echo $child['StudentID']; ?>" class="btn bg-purple me-2">View Grades</a>
                                <a href="feePayment.php?student=<?php echo $child['StudentID']; ?>" class="btn btnfee bg-purple me-2">Fees Payment</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php endforeach; ?>
        </div>
    </section>

    <script src="./js/profileparent.js"></script>
</body>
</html>
