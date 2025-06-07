<?php
include '../connection.php';
$StudentID=$_SESSION['StudentID'];



$query = "SELECT DISTINCT s.SubjectID, s.SubjectName, t.TeacherName, t.TeacherID
          FROM students st
          JOIN class c ON st.Class = c.ClassID
          JOIN schedule sch ON c.ClassID = sch.ClassID
          LEFT JOIN s_details sd ON sch.ScheduleID = sd.ScheduleID
          JOIN subjects s ON sd.SubjectID = s.SubjectID
          JOIN teachers t ON sd.TeacherID = t.TeacherID
          WHERE st.StudentID = $StudentID
          GROUP BY s.SubjectName
          ";
$run = mysqli_query($connect, $query);
$SubjNum=mysqli_num_rows($run);


$classQuery = "SELECT c.ClassID, g.GradeID 
FROM students st
JOIN class c ON st.Class = c.ClassID
JOIN grades g ON st.Grade = g.GradeID
WHERE st.StudentID = $StudentID";
$classResult = mysqli_query($connect, $classQuery);
$fetchh = mysqli_fetch_assoc($classResult);
$classId = $fetchh['ClassID'];


$Selectuser="SELECT * FROM `students` 
LEFT JOIN `class` ON `students`.`Class` = `class`.`ClassID`
  WHERE `StudentID`='$StudentID'";
$runUser=mysqli_query($connect, $Selectuser);

$fetch=mysqli_fetch_Assoc($runUser);
$name=$fetch['StudentName'];
$Email=$fetch['StudentEmail'];
$phone=$fetch['StudentNumber'];
$address=$fetch['StudentAddress'];
$grade=$fetch['Grade'];
$Class=$fetch['ClassName'];
$class=$fetch['Class'];
$pp=$fetch['Picture'];
// echo $StudentID;


$schedule = "SELECT * FROM `s_details`
            JOIN `teachers` ON `teachers`.`TeacherID` = `s_details`.`TeacherID`
            JOIN `subjects` ON `subjects`.`SubjectID` = `s_details`.`SubjectID`
            JOIN `schedule` ON `schedule`.`ScheduleID` = `s_details`.`ScheduleID`
            WHERE `ClassID` ='$class' AND `grade`='$grade'
            ORDER BY 
            FIELD(Weekday, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), PeriodNumber";
$scheduleRun = mysqli_query($connect, $schedule);

// Organize data by day and period
$scheduleData = array();
while ($row = mysqli_fetch_assoc($scheduleRun)) {
    $day = $row['Weekday'];
    $period = $row['PeriodNumber'];
    
    if (!isset($scheduleData[$day])) {
        $scheduleData[$day] = array();
    }
    
    $scheduleData[$day][$period] = array(
        'subject' => $row['SubjectName'],
        'teacher' => $row['TeacherName'],
        'start_time' => date('h:i A', strtotime($row['StartTime'])),
        'end_time' => date('h:i A', strtotime($row['EndTime']))
    );
}


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
    <link rel="stylesheet" href="./CSS/profileOmaima.css">
    <!-- <link rel="stylesheet" href="./CSS/profile.css"> -->

    <script src="./js/bootstrap.bundle.min.js"></script>
</head>
<body>
<header>
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


    <div class="container bg- min-vh-100 ">


        <div class="above row bg-body-secondary ">

            <div class="lift col-5  bg- p-3 mt-3">
                <div class="d-flex justify-content-evenly row ">
                <div class=" d-flex  justify-content-center">
                    <img src="../Media/<?php echo $pp ?>" alt="profile page" class="rounded-circle d-flex h-auto " height="100" width="100">
                    
                </div>
                <div class=" text-center ">
                    <h3 class="mt-2"><?php echo $name ?></h3>
                    <p class="mb-2"><?php echo $phone ?></p>
                    
                    
                </div>
                <div class="d-flex justify-content-center ">
                    <button class="btn bg-purple-200 p-2 rounded-full border me-4">
                        </i><a href="editprofile.php?edit=<?php echo $StudentID ?>"><i class="fa-solid fa-user-pen"></i></a> 
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

                <div class="downright row justify-content-center d-flex">
                    <div class="liftdown col-6">
                        <div>
                            <span class="text-gray">Class:</span>
                            <p class="text-purple"><?php echo $grade ." ". $Class ?></p>
                        </div>
                    
                        <div>
                            <span class="text-gray-700">Grade:</span>
                            <p class="text-purple-600"><?php echo $grade ?></p>
                        </div>
                    </div>
                    <div class="rightdown col-6">
                        <div>
                            <span class="text-gray-700">Subjects:</span>
                            <p class="text-purple-600"><?php echo $SubjNum?></p>
                        </div>
                        <div>
                            <span class="text-gray-700">Address:</span>
                            <p class="text-purple-600"><?php echo $address ?></p>
                        </div>
                        <div>
                            <span class="text-gray-700">Email:</span>
                             
                            <p class="text-purple-600"> <a href="mailto:<?php echo $Email ?>"><?php echo $Email ?></a></p>
                        </div>
                    </div>


                </div>
            
            
                




            </div>




        </div>
        
       

<!-- schedule          ========================================================= -->
<h1>My Weekly Class Schedule</h1>            
    <div class="schedule">
        <table class="timetable">
            <thead>
            <tr>
                    <th class="day-header">Day/Period</th>
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
                
                foreach ($days as $day): ?>
                    <tr>
                        <td class="day-header"><?= $day ?></td>
                        <?php for ($period = 1; $period <= 7; $period++): ?>
                            <td class="subject-cell">
                                <?php if (isset($scheduleData[$day][$period])): ?>
                                    <?php $class = $scheduleData[$day][$period]; ?>
                                    <div class="subject-name"><?= htmlspecialchars($class['subject']) ?></div>
                                    <div class="teacher-name"><?= htmlspecialchars($class['teacher']) ?></div>
                                    <?php if($period==4){  ?>

                                    <?php } ?>
                                <?php else: ?>
                                    <span class="free-period">Free</span>
                                <?php endif; ?>
                                <?php if($period==4){  ?>
<!-- Break Period (Fixed) -->
<td class="break-cell">BREAK</td>
                                    <?php } ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>
        






    </div>


</header>

<section class="bg- container  " id="classes">
    <h1 class="text-3xl font-bold text-center mt-4 mb-4 ">Classes</h1>
   <div class="row d-flex justify-content-evenly   "> 

   <?php foreach ($run as $card) { ?>
    <div class="card text-center bg-light mb-3 " style="width: 14rem; height: 10rem;">
        <div class="card-body">
            <h5 class="card-title"><?php echo $card['SubjectName'] ?></h5>
            <p class="card-text">Teacher:  <?php echo $card['TeacherName'] ?></p>
            <a href="SubjDetails.php?SubjectID=<?php echo $card['SubjectID'] ?>&TeacherID=<?php echo $card['TeacherID'] ?>&ClassID=<?php echo $classId ?>&GradeID=<?php echo $grade ?>"  class=" see btn rounded-4"> See details</a>
        </div>
    </div>
    <?php } ?>

    </div>

</section>
    
</body>
</html>
