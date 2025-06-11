<?php
include "../connection.php";

if(!isset($_SESSION['ParentID'])){
    header("location:login.php");
}

$ParentID = $_SESSION['ParentID'];
$StudentID = $_GET['student'];
$SubjectID = $_GET['SubjectID'];
$GradeID = $_GET['GradeID'];

// Get parent's subscription status
$subscriptionQuery = "SELECT Is_Subscribed FROM parents WHERE ParentID = $ParentID";
$subscriptionResult = mysqli_query($connect, $subscriptionQuery);
$isSubscribed = mysqli_fetch_assoc($subscriptionResult)['Is_Subscribed'];

// Verify that this student belongs to the logged-in parent
$verifyQuery = "SELECT * FROM family WHERE ParentID = '$ParentID' AND StudentID = '$StudentID'";
$verifyResult = mysqli_query($connect, $verifyQuery);
if(mysqli_num_rows($verifyResult) == 0) {
    header("location:ParentProfile.php");
    exit();
}

// Get subject details
$subjectQuery = "SELECT s.*, t.TeacherName, g.GradeNumber, c.ClassName 
                FROM subjects s
                JOIN s_details sd ON sd.SubjectID = s.SubjectID
                JOIN teachers t ON t.TeacherID = sd.TeacherID
                JOIN grades g ON g.GradeID = $GradeID
                JOIN class c ON c.ClassID = (SELECT Class FROM students WHERE StudentID = $StudentID)
                WHERE s.SubjectID = $SubjectID";
$subjectResult = mysqli_query($connect, $subjectQuery);
$subject = mysqli_fetch_assoc($subjectResult);

// Get all marks for this subject
$marksQuery = "SELECT * FROM marks 
               WHERE StudentID = $StudentID 
               AND SubjectID = $SubjectID 
               ORDER BY MarkType, MarkID";
$marksResult = mysqli_query($connect, $marksQuery);

// Get attendance statistics
$attendanceQuery = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN MarkValue = 0 THEN 1 ELSE 0 END) as absences,
    SUM(CASE WHEN MarkValue = 1 THEN 1 ELSE 0 END) as present
    FROM marks 
    WHERE StudentID = $StudentID 
    AND SubjectID = $SubjectID 
    AND MarkType = 'Attendance'";
$attendanceResult = mysqli_query($connect, $attendanceQuery);
$attendance = mysqli_fetch_assoc($attendanceResult);

// Get average marks by type for student
$averagesQuery = "SELECT 
    MarkType,
    AVG(MarkValue) as student_avg
    FROM marks 
    WHERE StudentID = $StudentID 
    AND SubjectID = $SubjectID 
    AND MarkType != 'Attendance'
    GROUP BY MarkType";
$averagesResult = mysqli_query($connect, $averagesQuery);
$studentAverages = [];
while($avg = mysqli_fetch_assoc($averagesResult)) {
    $studentAverages[$avg['MarkType']] = $avg['student_avg'];
}

// Get average marks by type for class
$classAveragesQuery = "SELECT 
    m.MarkType,
    AVG(m.MarkValue) as class_avg
    FROM marks m
    JOIN students s ON m.StudentID = s.StudentID
    WHERE s.Class = (SELECT Class FROM students WHERE StudentID = $StudentID)
    AND m.SubjectID = $SubjectID 
    AND m.MarkType != 'Attendance'
    GROUP BY m.MarkType";
$classAveragesResult = mysqli_query($connect, $classAveragesQuery);
$classAverages = [];
while($avg = mysqli_fetch_assoc($classAveragesResult)) {
    $classAverages[$avg['MarkType']] = $avg['class_avg'];
}

// Get marks grouped by type
$marksByType = [];
$marksQuery = "SELECT * FROM marks 
               WHERE StudentID = $StudentID 
               AND SubjectID = $SubjectID 
               ORDER BY MarkType, MarkID";
$marksResult = mysqli_query($connect, $marksQuery);
while($mark = mysqli_fetch_assoc($marksResult)) {
    if(!isset($marksByType[$mark['MarkType']])) {
        $marksByType[$mark['MarkType']] = [];
    }
    $marksByType[$mark['MarkType']][] = $mark;
}

// Get overall average for the subject
$overallAvgQuery = "SELECT AVG(MarkValue) as overall_avg 
                   FROM marks 
                   WHERE StudentID = $StudentID 
                   AND SubjectID = $SubjectID 
                   AND MarkType != 'Attendance'";
$overallAvgResult = mysqli_query($connect, $overallAvgQuery);
$overallAvg = mysqli_fetch_assoc($overallAvgResult)['overall_avg'];

// Get class average for comparison
$classAvgQuery = "SELECT AVG(m.MarkValue) as class_avg 
                 FROM marks m 
                 JOIN students s ON m.StudentID = s.StudentID 
                 WHERE s.Class = (SELECT Class FROM students WHERE StudentID = $StudentID)
                 AND m.SubjectID = $SubjectID 
                 AND m.MarkType != 'Attendance'";
$classAvgResult = mysqli_query($connect, $classAvgQuery);
$classAvg = mysqli_fetch_assoc($classAvgResult)['class_avg'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.4.0-web/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Subject Marks</title>
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
    top: 0;
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
        .marks-table {
            width: 100%;
            border-collapse: collapse;
        }
        .marks-table th, .marks-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .marks-table th {
            background-color: #f8f9fa;
        }
        .progress {
            height: 20px;
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }
        .performance-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .performance-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .performance-card .number {
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .performance-card .label {
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="bg-light">
    

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



    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="stats-card">
                    <h2><?php echo $subject['SubjectName'] ?></h2>
                    <p class="text-muted">
                        Teacher: <?php echo $subject['TeacherName'] ?> | 
                        Class: <?php echo $subject['GradeNumber'] . ' ' . $subject['ClassName'] ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Performance Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="performance-card">
                    <i class="fas fa-calendar-check text-success"></i>
                    <div class="number"><?php echo $attendance['present'] ?></div>
                    <div class="label">Classes Attended</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="performance-card">
                    <i class="fas fa-calendar-times text-danger"></i>
                    <div class="number"><?php echo $attendance['absences'] ?></div>
                    <div class="label">Absences</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="performance-card">
                    <i class="fas fa-chart-line text-primary"></i>
                    <div class="number"><?php echo round(($overallAvg / 100) * 100, 1) ?>%</div>
                    <div class="label">Overall Average</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="performance-card">
                    <i class="fas fa-users text-info"></i>
                    <div class="number"><?php echo round(($classAvg / 100) * 100, 1) ?>%</div>
                    <div class="label">Class Average</div>
                </div>
            </div>
        </div>

        <?php if($isSubscribed) { ?>
        <div class="row">
            <!-- Attendance Overview -->
            <div class="col-md-5">
                <div class="stats-card">
                    <h4>Attendance Overview</h4>
                    <div class="chart-container">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <p><strong>Total Classes:</strong> <?php echo $attendance['total'] ?></p>
                        <p><strong>Absences:</strong> <?php echo $attendance['absences'] ?></p>
                        <p><strong>Attendance Rate:</strong> 
                            <?php 
                            $rate = $attendance['total'] > 0 ? 
                                round(($attendance['present'] / $attendance['total']) * 100) : 0;
                            echo $rate . '%';
                            ?>
                        </p>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: <?php echo $rate ?>%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Overview -->
            <div class="col-md-7">
                <div class="stats-card">
                    <h4>Performance Overview</h4>
                    <div class="chart-container">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- Detailed Marks -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="stats-card">
                    <h4>Detailed Marks</h4>
                    <div class="row">
                        <!-- Attendance Section -->
                        <?php if(isset($marksByType['Attendance'])) { ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Attendance</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Mark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($marksByType['Attendance'] as $mark) { ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $mark['MarkValue'] == 1 ? 
                                                                '<span class="badge bg-success">Present</span>' : 
                                                                '<span class="badge bg-danger">Absent</span>' ?>
                                                        </td>
                                                        <td><?php echo $mark['MarkValue'] ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Quiz Section -->
                        <?php if(isset($marksByType['Quiz'])) { ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-pencil-alt me-2"></i>Quizzes</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Quiz</th>
                                                    <th>Mark</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($marksByType['Quiz'] as $index => $mark) { ?>
                                                    <tr>
                                                        <td>Quiz <?php echo $index + 1 ?></td>
                                                        <td><?php echo $mark['MarkValue'] ?></td>
                                                        <td>
                                                            <?php 
                                                            $status = '';
                                                            $color = '';
                                                            if($mark['MarkValue'] >= 9) {
                                                                $status = 'Excellent';
                                                                $color = 'success';
                                                            } elseif($mark['MarkValue'] >= 8) {
                                                                $status = 'Very Good';
                                                                $color = 'info';
                                                            } elseif($mark['MarkValue'] >= 7) {
                                                                $status = 'Good';
                                                                $color = 'primary';
                                                            } elseif($mark['MarkValue'] >= 6) {
                                                                $status = 'Pass';
                                                                $color = 'warning';
                                                            } else {
                                                                $status = 'Fail';
                                                                $color = 'danger';
                                                            }
                                                            ?>
                                                            <span class="badge bg-<?php echo $color ?>"><?php echo $status ?></span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Midterm Section -->
                        <?php if(isset($marksByType['Midterm'])) { ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Midterm</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Mark</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($marksByType['Midterm'] as $mark) { ?>
                                                    <tr>
                                                        <td><?php echo $mark['MarkValue'] ?></td>
                                                        <td>
                                                            <?php 
                                                            $status = '';
                                                            $color = '';
                                                            if($mark['MarkValue'] >= 45) {
                                                                $status = 'Excellent';
                                                                $color = 'success';
                                                            } elseif($mark['MarkValue'] >= 40) {
                                                                $status = 'Very Good';
                                                                $color = 'info';
                                                            } elseif($mark['MarkValue'] >= 35) {
                                                                $status = 'Good';
                                                                $color = 'primary';
                                                            } elseif($mark['MarkValue'] >= 25) {
                                                                $status = 'Pass';
                                                                $color = 'warning';
                                                            } else {
                                                                $status = 'Fail';
                                                                $color = 'danger';
                                                            }
                                                            ?>
                                                            <span class="badge bg-<?php echo $color ?>"><?php echo $status ?></span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Final Section -->
                        <?php if(isset($marksByType['Final'])) { ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Final</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Mark</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($marksByType['Final'] as $mark) { ?>
                                                    <tr>
                                                        <td><?php echo $mark['MarkValue'] ?></td>
                                                        <td>
                                                            <?php 
                                                            $status = '';
                                                            $color = '';
                                                            if($mark['MarkValue'] >= 90) {
                                                                $status = 'Excellent';
                                                                $color = 'success';
                                                            } elseif($mark['MarkValue'] >= 80) {
                                                                $status = 'Very Good';
                                                                $color = 'info';
                                                            } elseif($mark['MarkValue'] >= 70) {
                                                                $status = 'Good';
                                                                $color = 'primary';
                                                            } elseif($mark['MarkValue'] >= 60) {
                                                                $status = 'Pass';
                                                                $color = 'warning';
                                                            } else {
                                                                $status = 'Fail';
                                                                $color = 'danger';
                                                            }
                                                            ?>
                                                            <span class="badge bg-<?php echo $color ?>"><?php echo $status ?></span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Other Marks Section -->
                        <?php 
                        $otherTypes = array_diff(array_keys($marksByType), ['Attendance', 'Quiz', 'Midterm', 'Final']);
                        if(!empty($otherTypes)) {
                            foreach($otherTypes as $type) {
                        ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-star me-2"></i><?php echo $type ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Mark</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($marksByType[$type] as $mark) { ?>
                                                    <tr>
                                                        <td><?php echo $mark['MarkValue'] ?></td>
                                                        <td>
                                                            <?php 
                                                            $status = '';
                                                            $color = '';
                                                            if($mark['MarkValue'] >= 90) {
                                                                $status = 'Excellent';
                                                                $color = 'success';
                                                            } elseif($mark['MarkValue'] >= 80) {
                                                                $status = 'Very Good';
                                                                $color = 'info';
                                                            } elseif($mark['MarkValue'] >= 70) {
                                                                $status = 'Good';
                                                                $color = 'primary';
                                                            } elseif($mark['MarkValue'] >= 60) {
                                                                $status = 'Pass';
                                                                $color = 'warning';
                                                            } else {
                                                                $status = 'Fail';
                                                                $color = 'danger';
                                                            }
                                                            ?>
                                                            <span class="badge bg-<?php echo $color ?>"><?php echo $status ?></span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                            }
                        }
                        ?>
                    </div>
                </div>
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
                    data: [<?php echo $attendance['present'] . ',' . $attendance['absences'] ?>],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Performance Chart
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        new Chart(performanceCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($studentAverages)) ?>,
                datasets: [
                    {
                        label: 'Your Child',
                        data: <?php echo json_encode(array_values($studentAverages)) ?>,
                        backgroundColor: '#28a745',
                        barThickness: 20,
                        maxBarThickness: 25
                    },
                    {
                        label: 'Class Average',
                        data: <?php echo json_encode(array_values($classAverages)) ?>,
                        backgroundColor: '#ffc107',
                        barThickness: 20,
                        maxBarThickness: 25
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
    <?php } ?>
</body>
</html> 
