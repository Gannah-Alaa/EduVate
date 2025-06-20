<?php
include '../connection.php';

$teacherId = $_SESSION['TeacherID'];

// Get teacher details
$teacherQuery = "SELECT * FROM teachers 
JOIN subjects ON subjects.SubjectID= teachers.Subject 
JOIN roles ON roles.RoleID = teachers.RoleId
WHERE TeacherID = '$teacherId'";
$teacherResult = mysqli_query($connect, $teacherQuery);
$teacher = mysqli_fetch_assoc($teacherResult);
$name=$teacher['TeacherName'];
$phone=$teacher['TeacherNumber'];
$subj=$teacher['SubjectName'];
$role=$teacher['RoleTitle'];
$Email=$teacher['TeacherEmail'];
$pp=$teacher['TeacherPic'];


// ================= SCHEDULE SECTION =================
$periods = [
    1 => ['8:00:00', '8:45:00'],
    2 => ['8:45:00', '9:30:00'],
    3 => ['9:30:00', '10:15:00'],
    4 => ['10:15:00', '11:00:00'],
    'Break' => ['11:00:00', '11:30:00'],
    5 => ['11:30:00', '12:15:00'],
    6 => ['12:15:00', '13:00:00'],
    7 => ['13:00:00', '13:45:00']
];

$scheduleQuery = "SELECT sd.Weekday, sd.PeriodNumber, 
                 CONCAT(sch.grade, c.ClassName) AS GradeClass, 
                 s.SubjectName
                 FROM s_details sd
                 JOIN schedule sch ON sd.ScheduleID = sch.ScheduleID
                 JOIN class c ON sch.ClassID = c.ClassID
                 JOIN subjects s ON sd.SubjectID = s.SubjectID
                 WHERE sd.TeacherID = '$teacherId'
                 ORDER BY FIELD(sd.Weekday, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'), 
                 sd.PeriodNumber";
$scheduleResult = mysqli_query($connect, $scheduleQuery);

$scheduleData = [];
while ($row = mysqli_fetch_assoc($scheduleResult)) {
    $scheduleData[$row['Weekday']][$row['PeriodNumber']] = [
        'class' => $row['GradeClass'],
        'subject' => $row['SubjectName']
    ];
}

// ================= CLASS CARDS SECTION =================
$classesQuery = "SELECT 
                c.ClassID, 
                c.ClassName, 
                sch.grade,
                COUNT(st.StudentID) AS StudentCount
                FROM s_details sd
                JOIN schedule sch ON sd.ScheduleID = sch.ScheduleID
                JOIN class c ON sch.ClassID = c.ClassID
                LEFT JOIN students st ON st.Class = c.ClassID
                WHERE sd.TeacherID = '$teacherId'
                GROUP BY c.ClassID, c.ClassName, sch.grade
                ORDER BY sch.grade, c.ClassName";
$classesResult = mysqli_query($connect, $classesQuery);
$count=mysqli_num_rows($classesResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./CSS/bootstrap.min.css">
    <link rel="stylesheet" href="./CSS/all.min.css">
    <link rel="stylesheet" href="../fontawesome-free-6.4.0-web/css/all.min.css">

    <link rel="stylesheet" href="./CSS/profile.css">
    <!-- <link rel="stylesheet" href="./CSS/profile.css"> -->

    <script src="./js/bootstrap.bundle.min.js"></script>
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
    top: 0  ;
    left: 0;
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
.btn-outline-warning{
    background-color: transparent;
    
}
.btn-outline-warning a{
    color: #ffba00;
    background-color: transparent;
    text-decoration: none;
}

.btn-outline-warning a:hover{
    color: #0c3b2e;
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


.above i{
    color: #0c3b2e;
}

.above button:hover{
    background-color:rgb(255, 202, 57);
}

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        h1, h2 {
            color: #0c3b2e;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        h2 {
            /* border-bottom: 2px solid #6d9773; */
            padding-bottom: 8px;
            margin-top: 40px;
        }
        
        /* Schedule Table Styles */
        .schedule-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
        }
        .schedule-table th, .schedule-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .period-header {
            background-color: #0c3b2e;
            color: white;
            font-weight: bold;
        }
        .day-header {
            background-color: #0c3b2e;
            color: white;
            font-weight: bold;
        }
        .teaching-period {
            background-color: #6d9773;
            color: white;
        }
        .break-cell {
            background-color: #ffba00;
            font-style: italic;
            font-weight: bold;
        }
        
        /* Class Cards Styles */
        .classes-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .class-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 250px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
        }
        .class-header {
            border-bottom: 2px solid #6d9773;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .class-name {
            font-size: 1.5em;
            color: #0c3b2e;
            margin: 0;
        }
        .class-grade {
            font-size: 1.2em;
            color: #6d9773;
            margin: 5px 0;
        }
        .class-detail {
            margin: 5px 0;
            color: #555;
        }
        .detail-label {
            font-weight: bold;
            color: #0c3b2e;
        }
        .show-details {
            margin-top: auto;
            padding: 8px 0;
            text-align: center;
            background-color: #ffba00;
            color: #0c3b2e;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .show-details:hover {
            background-color: #e6a800;
        }
    </style>
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
                    <?php if(isset($_SESSION['TeacherID'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="TeacherProfile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="Chats.php">Chats</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="TeacherProfile.php#classes">Calsses</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php if(isset($_SESSION['TeacherID'])): ?>
                    <form method="post">
                        <button type="submit" name="logout" class="btn btn-outline-warning ms-3 me-4 ">Logout</button>
                    </form>
                <?php else: ?>
                    <button type="button" class="btn btn-outline-warning ms-3 me-4 "><a href="login.php">Login</a></button>
                <?php endif; ?>
            </div>
        </div>
    </nav> <br> <br><br> <br> <br> <br>
    <!-- end navbar -->



<header>

    <div class="container bg- min-vh-100 ">


        <div class="above row bg-body-secondary shadow">

            <div class="lift col-5  bg- p-3 ">
                <div class="d-flex justify-content-evenly row ">
                <div class=" d-flex  justify-content-center">
                    <img src="../Media/<?php echo $pp ?>" alt="profile page" class="rounded-circle d-flex h-auto " height="100" width="100">
                    
                </div>
                <div class=" text-center ">
                    <h3 class="mt-2"><?php echo $name ?></h3>
                    <p class="mb-2"><?php echo $subj." Teacher" ?></p>
                    
                    
                </div>
                <div class="d-flex justify-content-center ">
                    <button class="btn bg-purple-200 p-2 rounded-full border me-4">
                        </i><a href="editprofile.php?edit=<?php echo $teacherId ?>"><i class="fa-solid fa-user-pen"></i></a> 
                    </button>
                    <button class="btn bg-purple p-2 rounded-full border">
                       <a href="resetpass.php"><i class="fa-solid fa-key"></i></a> 
                    </button>
                </div>                
                </div>
                



            </div>




            <div class="right col-7  p-3">

                <div class="row ">
                    <h2 class="text-lg font-semibold ">Information </h2>
                    
                </div>

                <div class="downright row  ">
                    <div class="liftdown col-6">
                        <div>
                            <span class="text-gray">Level:</span>
                            <p class="text-purple"><?php echo $role ?></p>
                        </div>
                        <div>
                            <span class="text-gray-700">Number Of Classes:</span>
                            <p class="text-purple-600"><?php echo $count. " Classes" ?></p>
                        </div>
                        <div>
                            <span class="text-gray-700">Phone Number:</span>
                            <p class="text-purple-600"><?php echo $phone ?></p>
                        </div>
                    </div>
                    <div class="rightdown col-6">
                        <div>
                            <span class="text-gray-700">Subjects:</span>
                            <p class="text-purple-600"><?php echo $subj?></p>
                        </div>

                        <div>
                            <span class="text-gray-700">Email:</span>
                             
                            <p class="text-purple-600"> <a href="mailto:<?php echo $Email ?>"><?php echo $Email ?></a></p>
                        </div>
                    </div>


                </div>
            
            
                




            </div> 




        </div> <br><br>
        
        
        
        



    <h1><?php echo htmlspecialchars($teacher['TeacherName']) ?>'s Dashboard</h1>
    
    <!-- Weekly Schedule Section -->
    <div class="schedule-container">
        <h2>Weekly Schedule</h2>
        <table class="schedule-table">
            <thead>
                <tr>
                    <th class="period-header">Day/Period</th>
                    <th class="period-header">Period 1<br>8:00-8:45</th>
                    <th class="period-header">Period 2<br>8:45-9:30</th>
                    <th class="period-header">Period 3<br>9:30-10:15</th>
                    <th class="period-header">Period 4<br>10:15-11:00</th>
                    <th class="break-cell">Break<br>11:00-11:30</th>
                    <th class="period-header">Period 5<br>11:30-12:15</th>
                    <th class="period-header">Period 6<br>12:15-1:00</th>
                    <th class="period-header">Period 7<br>1:00-1:45</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
                foreach ($days as $day) { ?>
                    <tr>
                        <td class="day-header"><?php echo $day ?></td>
                        <?php foreach ([1, 2, 3, 4, 'Break', 5, 6, 7] as $period) { 
                            if ($period === 'Break') { ?>
                                <td class="break-cell">BREAK</td>
                            <?php } else { 
                                $classInfo = $scheduleData[$day][$period] ?? null; ?>
                                <td class="<?php echo $classInfo ? 'teaching-period' : '' ?>">
                                    <?php if ($classInfo) { 
                                        echo htmlspecialchars($classInfo['class']);
                                    } else { 
                                        echo 'Free';
                                    } ?>
                                </td>
                            <?php } 
                        } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <!-- Class Cards Section -->
    <div class="classes-container" id="classes">
        <h2 style="width: 100%; text-align: center; margin-bottom: 20px;">My Classes</h2>
        <?php while ($class = mysqli_fetch_assoc($classesResult)) { ?>
            <div class="class-card">
                <div class="class-header">
                    <h3 class="class-name">Class <?php echo htmlspecialchars($class['ClassName']) ?></h3>
                    <p class="class-grade">Grade <?php echo htmlspecialchars($class['grade']) ?></p>
                </div>
                
                <div class="class-details">
                    <p class="class-detail">
                        <span class="detail-label">Students:</span> 
                        <?php echo htmlspecialchars($class['StudentCount']) ?>
                    </p>
                </div>
                
                <a href="ClassDetails.php?class_id=<?php echo $class['ClassID'] ?>&grade=<?php echo $class['grade'] ?>" 
                   class="show-details">
                   Show Details
                </a>
            </div>
        <?php } ?>
    </div>
</body>
</html>
