<?php
include "../connection.php";


$StudentID = $_SESSION['StudentID'];


// Verify that this student belongs to the logged-in parent
// $verifyQuery = "SELECT * FROM family WHERE ParentID = '$ParentID' AND StudentID = '$StudentID'";
// $verifyResult = mysqli_query($connect, $verifyQuery);
// if(mysqli_num_rows($verifyResult) == 0) {
//     header("location:ParentProfile.php");
//     exit();
// }

// Get parent's subscription status
// $subscriptionQuery = "SELECT Is_Subscribed FROM parents WHERE ParentID = $ParentID";
// $subscriptionResult = mysqli_query($connect, $subscriptionQuery);
// $isSubscribed = mysqli_fetch_assoc($subscriptionResult)['Is_Subscribed'];

// Get student details
$studentQuery = "SELECT s.*, g.GradeNumber, c.ClassName 
                FROM students s 
                JOIN grades g ON s.Grade = g.GradeID 
                JOIN class c ON s.Class = c.ClassID 
                WHERE s.StudentID = '$StudentID'";
$studentResult = mysqli_query($connect, $studentQuery);
$student = mysqli_fetch_assoc($studentResult);

// Check if final marks exist
$finalMarksCheck = "SELECT COUNT(*) as final_count 
                   FROM marks 
                   WHERE StudentID = '$StudentID' 
                   AND MarkType = 'Final'";
$finalCheckResult = mysqli_query($connect, $finalMarksCheck);
$finalCount = mysqli_fetch_assoc($finalCheckResult)['final_count'];

// Get student's grades
$gradesQuery = "SELECT 
                s.SubjectName,
                COALESCE(SUM(CASE WHEN m.MarkType = 'Classwork' THEN m.MarkValue ELSE 0 END), 0) as classwork_score,
                COALESCE(SUM(CASE WHEN m.MarkType = 'Midterm' THEN m.MarkValue ELSE 0 END), 0) as midterm_score,
                COALESCE(SUM(CASE WHEN m.MarkType = 'Final' THEN m.MarkValue ELSE 0 END), 0) as final_score
                FROM subjects s
                LEFT JOIN marks m ON s.SubjectID = m.SubjectID AND m.StudentID = '$StudentID'
                WHERE s.SubjectID IN (
                    SELECT sd.SubjectID 
                    FROM s_details sd 
                    JOIN schedule sch ON sd.ScheduleID = sch.ScheduleID 
                    WHERE sch.ClassID = '{$student['Class']}'
                )
                GROUP BY s.SubjectID, s.SubjectName";
$gradesResult = mysqli_query($connect, $gradesQuery);

// Function to calculate grade based on total score
function calculateGrade($total) {
    if ($total >= 90) return "A+";
    if ($total >= 85) return "A";
    if ($total >= 80) return "B+";
    if ($total >= 75) return "B";
    if ($total >= 70) return "C+";
    if ($total >= 65) return "C";
    if ($total >= 60) return "D+";
    if ($total >= 50) return "D";
    return "F";
}

// Calculate overall performance
$total_subjects = mysqli_num_rows($gradesResult);
$total_percentage = 0;

// Reset the pointer to the beginning of the result set
mysqli_data_seek($gradesResult, 0);

while ($grade = mysqli_fetch_assoc($gradesResult)) {
    $classwork_percentage = ($grade['classwork_score'] / 30) * 25; // 25% of total
    $midterm_percentage = ($grade['midterm_score'] / 50) * 25; // 25% of total
    $final_percentage = ($grade['final_score'] / 100) * 50; // 50% of total
    $total_percentage += ($classwork_percentage + $midterm_percentage + $final_percentage);
}

$overall_percentage = round($total_percentage / $total_subjects, 2);
$overall_grade = calculateGrade($overall_percentage);

// Reset the pointer again for the display loop
mysqli_data_seek($gradesResult, 0);

// Prepare data for charts
$subjects = [];
$classworkScores = [];
$midtermScores = [];
$finalScores = [];
$grades = [];

while ($grade = mysqli_fetch_assoc($gradesResult)) {
    $subjects[] = $grade['SubjectName'];
    $classworkScores[] = $grade['classwork_score'];
    $midtermScores[] = $grade['midterm_score'];
    $finalScores[] = $grade['final_score'];
    
    $classwork_percentage = ($grade['classwork_score'] / 30) * 25;
    $midterm_percentage = ($grade['midterm_score'] / 50) * 25;
    $final_percentage = ($grade['final_score'] / 100) * 50;
    $total_percentage = $classwork_percentage + $midterm_percentage + $final_percentage;
    $grades[] = calculateGrade($total_percentage);
}

// Reset pointer one more time for the main display
mysqli_data_seek($gradesResult, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Report</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/all.min.css">
    <link rel="stylesheet" href="./css/finalreport.css">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background-color: #0c3b2e;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

body {
    padding-top: 76px; /* Height of the navbar */
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
        <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand logo" href="home.php">EduVate</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-4">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="finalReport.php">Marks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="Profile.php#classes">Subjects</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- end navbar -->
    <div class="container mt-5">
        <div class="report-card">
            <h1 class="text-6xl font-bold text-center mt-4 mb-4">Final Report</h1>
            
            <?php if($finalCount == 0): ?>
            <div class="alert alert-warning text-center">
                <h4>Final Report is not available yet</h4>
                <p>Final marks have not been entered into the system.</p>
            </div>
            <?php else: ?>
            
            <div class="student-info mb-4 p-4 rounded-lg shadow-sm">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="info-group mb-3">
                            <label class="text-gray-700 mb-1 d-block">Student Name:</label>
                            <p class="text-green fw-bold mb-0"><?php echo htmlspecialchars($student['StudentName']); ?></p>
                        </div>
                        <div class="info-group">
                            <label class="text-gray-700 mb-1 d-block">Semester:</label>
                            <p class="text-green fw-bold mb-0">First Semester 2024</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="info-group mb-3 text-md-end">
                            <label class="text-gray-700 mb-1 d-block">Grade:</label>
                            <p class="text-green fw-bold mb-0"><?php echo htmlspecialchars($student['GradeNumber']); ?></p>
                        </div>
                        <div class="info-group text-md-end">
                            <label class="text-gray-700 mb-1 d-block">Class:</label>
                            <p class="text-green fw-bold mb-0"><?php echo htmlspecialchars($student['ClassName']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="min-w-full rounded-lg shadow-md">
                    <thead>
                        <tr class="topraw">
                            <th class="py-2 px-3 text-white text-center">Subjects</th>
                            <th class="py-2 px-3 text-white text-center">Classwork<br>(50)</th>
                            <th class="py-2 px-3 text-white text-center">Midterm<br>(50)</th>
                            <th class="py-2 px-3 text-white text-center">Final<br>(100)</th>
                            <th class="py-2 px-3 text-white text-center">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($grade = mysqli_fetch_assoc($gradesResult)): 
                            $classwork_percentage = ($grade['classwork_score'] / 30) * 25;
                            $midterm_percentage = ($grade['midterm_score'] / 50) * 25;
                            $final_percentage = ($grade['final_score'] / 100) * 50;
                            $total_percentage = $classwork_percentage + $midterm_percentage + $final_percentage;
                            $subject_grade = calculateGrade($total_percentage);
                        ?>
                        <tr>
                            <td class="py-2 px-3 text-center"><?php echo htmlspecialchars($grade['SubjectName']); ?></td>
                            <td class="py-2 px-3 text-center">
                                <?php 
                                // Display classwork score
                                echo htmlspecialchars($grade['classwork_score']); 
                                ?>
                            </td>
                            <td class="py-2 px-3 text-center"><?php echo htmlspecialchars($grade['midterm_score']); ?></td>
                            <td class="py-2 px-3 text-center"><?php echo htmlspecialchars($grade['final_score']); ?></td>
                            <td class="py-2 px-3 text-center"><?php echo $subject_grade; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <td class="py-2 px-3 text-green fw-bold text-center">Overall Performance</td>
                            <td colspan="3" class="py-2 px-3 text-end fw-bold">Total:</td>
                            <td class="py-2 px-3 text-center fw-bold text-green"><?php echo $overall_percentage; ?>% <?php echo $overall_grade; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Analytics Section -->
            <!-- <div class="analytics-section">
                <?php if($isSubscribed): ?>
                    <h4 class="mb-4">Performance Analytics</h4>
                    <div class="chart-container" style="position: relative; height:400px;">
                        <canvas id="performanceChart"></canvas>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <h4>Unlock Advanced Analytics</h4>
                        <p>Subscribe to access detailed performance analytics and insights.</p>
                        <a href="subscription.php" class="btn-subscribe">Subscribe Now</a>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($finalCount > 0 && $isSubscribed): ?>
    <script>
        const ctx = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($subjects); ?>,
                datasets: [
                    {
                        label: 'Classwork (50)',
                        data: <?php echo json_encode($classworkScores); ?>,
                        backgroundColor: 'rgba(34, 104, 85, 0.99)', // Dark green (#0C3B2E) with opacity
                        borderColor: 'rgba(12, 59, 46, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Midterm (50)',
                        data: <?php echo json_encode($midtermScores); ?>,
                        backgroundColor: 'rgba(255, 193, 7, 0.93)', // Yellow (#ffc107) with opacity
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Final (100)',
                        data: <?php echo json_encode($finalScores); ?>,
                        backgroundColor: 'rgb(12, 59, 46)', // Faded dark green
                        borderColor: 'rgba(12, 59, 46, 0.8)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Subject-wise Performance Comparison',
                        color: '#0C3B2E', // Dark green for title
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#0C3B2E', // Dark green for legend text
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(12, 59, 46, 0.1)' // Light grid lines
                        },
                        ticks: {
                            color: '#0C3B2E' // Dark green for y-axis numbers
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(12, 59, 46, 0.1)' // Light grid lines
                        },
                        ticks: {
                            color: '#0C3B2E' // Dark green for x-axis labels
                        }
                    }
                }
            }
        });
    </script>
    <?php endif; ?> -->
</body>
</html> 