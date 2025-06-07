<?php
include "../connection.php";

if(!isset($_SESSION['ParentID'])){
    header("location:login.php");
}

$ParentID = $_SESSION['ParentID'];
$StudentID = $_GET['student'];

// Get parameters from URL
$subjectId = isset($_GET['SubjectID']) ? $_GET['SubjectID'] : 0;
$teacherId = isset($_GET['TeacherID']) ? $_GET['TeacherID'] : 0;
$classId = isset($_GET['ClassID']) ? $_GET['ClassID'] : 0;
$gradeId = isset($_GET['GradeID']) ? $_GET['GradeID'] : 0;

// Verify that this student belongs to the logged-in parent
$verifyQuery = "SELECT * FROM family WHERE ParentID = '$ParentID' AND StudentID = '$StudentID'";
$verifyResult = mysqli_query($connect, $verifyQuery);
if(mysqli_num_rows($verifyResult) == 0) {
    header("location:ParentProfile.php");
    exit();
}

$detailsQuery = "SELECT *
                 FROM subjects s
                 JOIN teachers t ON t.TeacherID = $teacherId
                 JOIN class c ON c.ClassID = $classId
                 JOIN grades g ON g.GradeID = $gradeId
                 WHERE s.SubjectID = $subjectId";
$detailsResult = mysqli_query($connect, $detailsQuery);
$details = mysqli_fetch_assoc($detailsResult);

// Get students in this class
$studentsQuery = "SELECT s.*, 
                 (SELECT COUNT(*) FROM marks m WHERE m.StudentID = s.StudentID AND m.SubjectID = $subjectId AND m.MarkType = 'Attendance' AND m.MarkValue = 0) as absences
                 FROM students s 
                 WHERE s.Class = $classId AND s.Grade = $gradeId
                 ORDER BY s.StudentName";
$studentsResult = mysqli_query($connect, $studentsQuery);

// Get total number of students
$totalStudents = mysqli_num_rows($studentsResult);

// Get total materials posted
$materialsCountQuery = "SELECT COUNT(*) as total FROM Material WHERE ClassID = $classId AND TeacherID = $teacherId";
$materialsCountResult = mysqli_query($connect, $materialsCountQuery);
$materialsCount = mysqli_fetch_assoc($materialsCountResult)['total'];

// Get parent subscription status
$subscriptionQuery = "SELECT Is_Subscribed FROM parents WHERE ParentID = $ParentID";
$subscriptionResult = mysqli_query($connect, $subscriptionQuery);
$isSubscribed = mysqli_fetch_assoc($subscriptionResult)['Is_Subscribed'];

// Get student's average in this subject
$studentAvgQuery = "SELECT AVG(MarkValue) as student_avg 
                   FROM marks 
                   WHERE StudentID = $StudentID 
                   AND SubjectID = $subjectId 
                   AND MarkType != 'Attendance'";
$studentAvgResult = mysqli_query($connect, $studentAvgQuery);
$studentAvg = mysqli_fetch_assoc($studentAvgResult)['student_avg'];

// Get class average for comparison
$classAvgQuery = "SELECT AVG(m.MarkValue) as class_avg 
                 FROM marks m 
                 JOIN students s ON m.StudentID = s.StudentID 
                 WHERE s.Class = $classId 
                 AND m.SubjectID = $subjectId 
                 AND m.MarkType != 'Attendance'";
$classAvgResult = mysqli_query($connect, $classAvgQuery);
$classAvg = mysqli_fetch_assoc($classAvgResult)['class_avg'];

$materialsQuery = "SELECT * FROM Material 
                   WHERE ClassID = $classId AND TeacherID = $teacherId
                   ORDER BY MaterialID DESC";
$materialsResult = mysqli_query($connect, $materialsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/subject details.css">
    <link rel="stylesheet" href="fontawesome-free-6.4.0-web/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Subject details</title>
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
    
    .after-effect a {
        display: inline-block;
        width: 100%;
        text-align: center;
    }
    
    .btn-outline-warning {
        margin: 10px auto !important;
        display: block;
    }
}
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .student-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .student-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .student-item.current {
            background-color: #fff3cd;
            font-weight: bold;
        }
        .analytics-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-subscribe {
            background: #ffc107;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn-subscribe:hover {
            background: #e0a800;
            color: white;
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
                        <a class="nav-link" aria-current="page" href="ParentProfile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="Subscription.php">Subscription</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="Chats.php">Chatting</a>
                    </li>
                </ul>
                <form method="post">
                <button type="submit" name="logout" class=" log btn btn-outline-warning ms-3 me-4 ">Logout</button>
                </form>
            </div>
        </div>
    </nav> <br><br><br>
    <!-- end navbar -->


    <div class="container">
        <div class="row">
            <!-- Left Section - Subject Info -->
            <div class="col-lg-3 mt-5">
                <div class="stats-card mt-5">
                    <div class="text-center">
                        <h3><?php echo $details['SubjectName'] ?></h3>
                        <h6>Teacher: <?php echo $details['TeacherName'] ?></h6>
                        <h6><?php echo $gradeId . $details['ClassName'] ?></h6>
                        
                        <div class="mt-4">
                            <a href="SubjectMarks.php?student=<?php echo $StudentID ?>&SubjectID=<?php echo $subjectId ?>&GradeID=<?php echo $gradeId ?>" class="btn btn-warning w-100 mb-2">
                                <i class="fa-solid fa-chart-line"></i> View Performance
                            </a>
                            
                            <?php if(!empty($details['Ebooks'])) { ?>
                            <a href="../Media/Uploads/<?php echo $details['Ebooks'] ?>" download class="btn btn-warning w-100">
                                <i class="fa-solid fa-download"></i> Download E-book
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Class Statistics -->
                <div class="stats-card">
                    <h5>Class Statistics</h5>
                    <p><i class="fas fa-users"></i> Total Students: <?php echo $totalStudents ?></p>
                    <p><i class="fas fa-file-alt"></i> Materials Posted: <?php echo $materialsCount ?></p>
                    <?php if($isSubscribed) { ?>
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6>Performance Comparison</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Your Child</small>
                                <h5 class="mb-0"><?php echo round($studentAvg, 1) ?></h5>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">Class Average</small>
                                <h5 class="mb-0"><?php echo round($classAvg, 1) ?></h5>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: <?php echo ($studentAvg / 100) * 100 ?>%">
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Students List -->
                <div class="stats-card">
                    <h5>Class Students</h5>
                    <div class="student-list">
                        <?php while($student = mysqli_fetch_assoc($studentsResult)) { ?>
                            <div class="student-item <?php echo ($student['StudentID'] == $StudentID) ? 'current' : '' ?>">
                                <?php echo $student['StudentName'] ?>
                                <?php if($student['StudentID'] == $StudentID) { ?>
                                    <span class="badge bg-warning">Your Child</span>
                                <?php } ?>
                            </div>
                        <?php } ?>
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
                                    <small class="text-muted"><?php echo date('F j, Y', strtotime($material['MaterialID'])) ?></small>
                                </div>
                            </div>
                            <div class="p-3">
                                <h5><?php echo $material['Title'] ?></h5>
                                <p class="mb-3"><?php echo nl2br($material['body']) ?></p>
                                <?php if (!empty($material['Material'])) { ?>
                                    <a href="../Media/Uploads/<?php echo $material['Material'] ?>" download class="btn btn-outline-warning">
                                        <i class="fa-solid fa-download"></i> Download Attachment
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="alert alert-info mt-3">
                        No materials have been uploaded yet for this subject.
                    </div>
                <?php } ?>

                <?php if($isSubscribed) { ?>
                <!-- Analytics Section (Only visible to subscribed parents) -->
                <div class="analytics-section">
                    <h4>Performance Analytics</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="marksChart"></canvas>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="analytics-section text-center">
                    <h4>Unlock Advanced Analytics</h4>
                    <p>Subscribe to access detailed performance analytics and insights.</p>
                    <a href="subscription.php" class="btn-subscribe">Subscribe Now</a>
                    <!-- <button class="btn-subscribe">Subscribe Now</button> -->
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <?php if($isSubscribed) { ?>
    <script>
        // Attendance Chart
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(attendanceCtx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    data: [<?php 
                        $attendanceQuery = "SELECT 
                            SUM(CASE WHEN MarkValue = 1 THEN 1 ELSE 0 END) as present,
                            SUM(CASE WHEN MarkValue = 0 THEN 1 ELSE 0 END) as absent
                            FROM marks 
                            WHERE StudentID = $StudentID 
                            AND SubjectID = $subjectId 
                            AND MarkType = 'Attendance'";
                        $attendanceResult = mysqli_query($connect, $attendanceQuery);
                        $attendance = mysqli_fetch_assoc($attendanceResult);
                        echo $attendance['present'] . ',' . $attendance['absent'];
                    ?>],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Attendance Overview'
                    }
                }
            }
        });

        // Marks Chart
        const marksCtx = document.getElementById('marksChart').getContext('2d');
        new Chart(marksCtx, {
            type: 'line',
            data: {
                labels: ['Quiz 1', 'Quiz 2', 'Quiz 3', 'Quiz 4'],
                datasets: [{
                    label: 'Marks',
                    data: [<?php 
                        $marksQuery = "SELECT MarkValue 
                            FROM marks 
                            WHERE StudentID = $StudentID 
                            AND SubjectID = $subjectId 
                            AND MarkType IN ('Quiz')
                            ORDER BY MarkID";
                        $marksResult = mysqli_query($connect, $marksQuery);
                        $marks = [];
                        while($mark = mysqli_fetch_assoc($marksResult)) {
                            $marks[] = $mark['MarkValue'];
                        }
                        echo implode(',', $marks);
                    ?>],
                    borderColor: '#ffc107',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Marks Progression'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
    <?php } ?>
</body>
</html> 
