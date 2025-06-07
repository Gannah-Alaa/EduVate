
<?php
include '../connection.php';

// Get parameters from URL
$subjectId = isset($_GET['SubjectID']) ? $_GET['SubjectID'] : 0;
$teacherId = isset($_GET['TeacherID']) ? $_GET['TeacherID'] : 0;
$classId = isset($_GET['ClassID']) ? $_GET['ClassID'] : 0;
$gradeId = isset($_GET['GradeID']) ? $_GET['GradeID'] : 0;

$studentId = $_SESSION['StudentID'];

if(isset($_GET['delete'])){
    $commenttt=$_GET['delete'];

    $del="DELETE FROM submitions WHERE SubmitID=$commenttt";
    $rundel=mysqli_query($connect,$del);
    header("location:subjDetails.php?SubjectID=$subjectId&&TeacherID=$teacherId&&ClassID=$classId&&GradeID=$gradeId");
}

$st="SELECT * FROM students WHERE StudentID=$studentId";
$rst=mysqli_query($connect,$st);
$stud=mysqli_fetch_assoc($rst);


$detailsQuery = "SELECT *
                 FROM subjects s
                 JOIN teachers t ON t.TeacherID = $teacherId
                 JOIN class c ON c.ClassID = $classId
                 JOIN grades g ON g.GradeID = $gradeId
                 WHERE s.SubjectID = $subjectId";
$detailsResult = mysqli_query($connect, $detailsQuery);
$details = mysqli_fetch_assoc($detailsResult);


$materialsQuery = "SELECT * FROM Material 
                   WHERE ClassID = $classId AND TeacherID = $teacherId
                   ORDER BY MaterialID DESC";
$materialsResult = mysqli_query($connect, $materialsQuery);


if(isset($_POST['submitcomment'])){
    $materialId = $_POST['material_id'];
    $comment = mysqli_real_escape_string($connect, $_POST['comment']);
    $media = '';

    if ($_FILES['media']['tmp_name']){
        $media=$_FILES['media']['name'];
        move_uploaded_file($_FILES['media']['tmp_name'],"../Media/submissions/".$media);
    }    
    $insertQuery = "INSERT INTO submitions (MaterialID, StudentID, Comment, Media)
                    VALUES ($materialId, $studentId, '$comment', '$media')";
    mysqli_query($connect, $insertQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/subject details.css">
    <link rel="stylesheet" href="cssf/all.min.css">    <title>Subject details</title>
    <style>
        .nav-link{
            position: relative;
            color: #ffba00;
        }
        .nav-link:hover{
            color: #ffba00;
        }
        .nav-link::after{
            content: "";
            width: 0;
            height: 3px;
            background-color: #ffba00; 
            position: absolute;
            left: 10px;
            bottom: 0;
            transition:  0.5s ;
        }
        .nav-link:hover::after{
            width: 70%;
        }
        .navbar{
            background-color: #0c3b2e;
            position: fixed;
            width: 100%;
            z-index: 20;
        }
        .logo{
            font-size: 40px;
            font-weight: 800;
            color: #ffba00;
        }
        .logo:hover{
            color: #ffba00;
        }
        .nav-item a{
            color: #ffba00;
        }
        .navbar-toggler {
            border: 2px solid #ffba00;
            padding: 8px;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 186, 0, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        @media (max-width: 991px) {
            .navbar-nav {
                padding: 20px 0;
            }
            .nav-item {
                margin: 10px 0;
            }
        }
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .post {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .comments-area {
            scrollbar-width: thin;
            scrollbar-color: #ffba00 #f8f9fa;
        }
        .comments-area::-webkit-scrollbar {
            width: 6px;
        }
        .comments-area::-webkit-scrollbar-thumb {
            background: #ffba00;
            border-radius: 4px;
        }
        .comments-area::-webkit-scrollbar-track {
            background: #f8f9fa;
        }
    </style>
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
                        <a class="nav-link" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#aboutus-container">AboutUs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#events">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php#contactus">ContactUs</a>
                    </li>
                    <?php if(isset($_SESSION['StudentID'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="Profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="FinalReport.php">Marks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="Profile.php#classes">Subjects</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php if(isset($_SESSION['StudentID'])): ?>
                    <form method="post">
                        <button type="submit" name="logout" class="btn btn-outline-warning ms-3 me-4 ">Logout</button>
                    </form>
                <?php else: ?>
                    <button type="button" class="btn btn-outline-warning ms-3 me-4 "><a href="login.php">Login</a></button>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <!-- end navbar -->




      
                

      <!-- start right section -->

  <div class="container" style="margin-top: 90px;">
    <div class="row">
        <!-- Left Section - Subject Info -->
        <div class="col-lg-3 mt-5">
            <div class="stats-card mt-5">
                <div class="text-center">
                    <h3><?php echo $details['SubjectName'] ?></h3>
                    <h6>Teacher: <?php echo $details['TeacherName'] ?></h6>
                    <h6><?php echo $gradeId . $details['ClassName'] ?></h6>
                    <div class="mt-4">
                        <a href="submarks.php?SubjectID=<?php echo $details['SubjectID']?>&GradeID=<?php echo $details['GradeID']?>" class="btn btn-warning w-100 mb-2">
                            <i class="fa-solid fa-ranking-star"></i> View marks
                        </a>
                        <?php if(!empty($details['Ebooks'])) { ?>
                        <a href="../Media/Uploads/<?php echo $details['Ebooks'] ?>" download class="btn btn-warning w-100">
                            <i class="fa-solid fa-download"></i> Download E-book
                        </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right Section - Materials -->
        <div class="col-lg-9">
            <?php if (mysqli_num_rows($materialsResult) > 0) { ?>
                <?php foreach ($materialsResult as $material) { ?>
                    <div class="post bg-white shadow mt-3">
                        <div class="post-info d-flex p-3">
                            <img src="../Media/<?php echo $details['TeacherPic'] ?>" alt="" width="60px" height="60px" class="rounded-circle">
                            <div class="ms-3">
                                <h6 class="mb-0"><?php echo $details['TeacherName'] ?></h6>
                            </div>
                        </div>
                        <div class="p-3">
                            <h5><?php echo isset($material['Title']) ? $material['Title'] : '' ?></h5>
                            <p class="mb-3"><?php echo nl2br($material['body']) ?></p>
                            <?php if (!empty($material['Material'])) { ?>
                                <a href="../Media/Uploads/<?php echo $material['Material'] ?>" download class="btn btn-outline-warning">
                                    <i class="fa-solid fa-download"></i> Download Attachment
                                </a>
                            <?php } ?>
                            <!-- Reply section -->
                            <form method="post" enctype="multipart/form-data" class="mt-3">
                                <input type="hidden" name="material_id" value="<?php echo $material['MaterialID'] ?>">
                                <div class="input-group mb-2">
                                    <input type="text" name="comment" class="form-control" placeholder="Type your reply..." required>
                                    <input type="file" name="media" id="fileInput<?php echo $material['MaterialID'] ?>" style="display: none;">
                                    <button class="btn btn-warning" type="button" onclick="document.getElementById('fileInput<?php echo $material['MaterialID'] ?>').click()">
                                        <i class="fa-solid fa-paperclip"></i>
                                    </button>
                                    <button class="btn btn-warning" name="submitcomment" type="submit">
                                        <i class="fa-solid fa-paper-plane"></i>
                                    </button>
                                </div>
                                <span id="selectedFileName<?php echo $material['MaterialID'] ?>" class="text-muted small"></span>
                                <script>
                                    document.getElementById('fileInput<?php echo $material['MaterialID'] ?>').addEventListener('change', function() {
                                        var fileName = this.files[0]?.name || 'No file selected';
                                        document.getElementById('selectedFileName<?php echo $material['MaterialID'] ?>').textContent = fileName;
                                    });
                                </script>
                            </form>
                            <!-- Replies/comments area -->
                            <?php
                                $submissionsQuery = "SELECT * FROM submitions WHERE MaterialID = {$material['MaterialID']} AND StudentID = $studentId ORDER BY SubmitID DESC";
                                $runSubmit = mysqli_query($connect, $submissionsQuery);
                                $count = mysqli_num_rows($runSubmit);
                            ?>
                            <div class="comments-area mt-2 p-2 bg-light rounded" style="max-height: 120px; overflow-y: auto; font-size: 0.95em;">
                                <?php if ($count > 0) { ?>
                                    <?php foreach($runSubmit as $comm) { ?>
                                        <div class="d-flex align-items-start mb-2">
                                            <img src="../Media/<?php echo $stud['Picture'] ?>" alt="" width="35px" height="35px" class="rounded-circle me-2">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold"><?php echo $stud['StudentName'] ?></span>
                                                    <a href="subjDetails.php?delete=<?php echo $comm['SubmitID'] ?>&SubjectID=<?php echo $subjectId?>&TeacherID=<?php echo $teacherId?>&ClassID=<?php echo $classId?>&GradeID=<?php echo $gradeId ?>" class="text-danger ms-2" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                                </div>
                                                <?php if (!empty($comm['Comment'])) { ?>
                                                    <div><?php echo nl2br(htmlspecialchars($comm['Comment'])); ?></div>
                                                <?php } ?>
                                                <?php if (!empty($comm['Media'])) { ?>
                                                    <div><a href="../Media/submissions/<?php echo htmlspecialchars($comm['Media']); ?>" download><i class="fas fa-file-download"></i> <?php echo htmlspecialchars($comm['Media']); ?></a></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="text-muted">No replies yet.</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="alert alert-info mt-3">
                    No materials have been uploaded yet for this subject.
                </div>
            <?php } ?>
        </div>
    </div>
</div>
        
  <script src="js/subject details.js"></script>


</body>
</html>
