<?php
include '../connection.php';

if(!isset($_SESSION['TeacherID'])) {
    header("Location: login.php");
}

$class_id = $_GET['class_id'];
$grade = $_GET['grade'];
$teacher_id = $_SESSION['TeacherID'];

// Upload content form
if(isset($_POST['upload'])) {
    $post_content = mysqli_real_escape_string($connect, $_POST['post_content']);
    $Matt = '';
    
    if (!empty($_FILES['materiall']['tmp_name'])) {
        $Matt = $_FILES['materiall']['name'];
        // Generate unique filename to prevent conflicts
        $image = uniqid() . '_' . $Matt;
        move_uploaded_file($_FILES['materiall']['tmp_name'], "../Media/Uploads/" . $Matt);

    }
    $insert_query = "INSERT INTO material (TeacherID, ClassID, body, Material) 
                    VALUES ('$teacher_id', '$class_id', '$post_content', '$Matt')";
    mysqli_query($connect, $insert_query);
    
    header("Location: ClassDetails.php?class_id=$class_id&grade=$grade");
    exit;
}

// Delete Form
if(isset($_GET['delete_material'])) {
    $material_id = $_GET['delete_material'];
    
    $verify_query = "SELECT Material FROM material WHERE MaterialID = '$material_id' AND TeacherID = '$teacher_id'";
    $verify_result = mysqli_query($connect, $verify_query);
    
    if(mysqli_num_rows($verify_result) > 0) {
        $material = mysqli_fetch_assoc($verify_result);
        
        
        $delete_query = "DELETE FROM material WHERE MaterialID = '$material_id'";
        mysqli_query($connect, $delete_query);
        
        $delete_submissions = "DELETE FROM submitions WHERE MaterialID = '$material_id'";
        mysqli_query($connect, $delete_submissions);
        
        header("Location: ClassDetails.php?class_id=$class_id&grade=$grade");
        
    }
}

$teacherQ = "SELECT * FROM teachers WHERE TeacherID = $teacher_id";
$RTeacher = mysqli_query($connect, $teacherQ);
$FTeacher = mysqli_fetch_assoc($RTeacher);


$verify_query = "SELECT c.* FROM class c
                JOIN schedule sch ON c.ClassID = sch.ClassID
                JOIN s_details sd ON sd.ScheduleID = sch.ScheduleID
                WHERE c.ClassID = '$class_id' AND sd.TeacherID = '$teacher_id'";
$verify_result = mysqli_query($connect, $verify_query);

if(mysqli_num_rows($verify_result) == 0) {
    header("Location: profile.php");
    exit;
}

$class = mysqli_fetch_assoc($verify_result);

// Get students in this class
$students_query = "SELECT s.* FROM students s
                  WHERE s.Class = '$class_id' AND s.Grade='$grade'
                  ORDER BY s.StudentName";
$students_result = mysqli_query($connect, $students_query);
$student_count = mysqli_num_rows($students_result);

// Get materials for this class
$materials_query = "SELECT m.*, t.TeacherName 
                   FROM material m
                   JOIN teachers t ON m.TeacherID = t.TeacherID
                   WHERE m.ClassID = '$class_id'
                   ORDER BY m.MaterialID DESC"; // Show latest first
$materials_result = mysqli_query($connect, $materials_query);

if(isset($_POST['Attend']) || isset($_POST['Absent'])) {
    $student_id = $_POST['student_id'];

    // Always fetch the latest class info to ensure SubjectID and GradeID are available
    $class_query = "SELECT * FROM class WHERE ClassID='$class_id'";
    $class_result = mysqli_query($connect, $class_query);
    $class = mysqli_fetch_assoc($class_result);

    $subject_id = isset($class['SubjectID']) ? $class['SubjectID'] : '';
    $grade_id = isset($class['GradeID']) ? $class['GradeID'] : '';

    if ($student_id && $subject_id && $grade_id) {
        $mark_value = isset($_POST['Attend']) ? 1 : 0;
        $attendance_query = "INSERT INTO marks (SubjectID, StudentID, MarkType, MarkValue, Semester, GradeID)
                             VALUES ('$subject_id', '$student_id', 'Attendance', $mark_value, 1, '$grade_id')";
        mysqli_query($connect, $attendance_query);
        header("Location: ClassDetails.php?class_id=$class_id&grade=$grade");
        exit;
    } else {
        echo "<script>alert('Error: Missing student, subject, or grade information.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $grade . $class['ClassName']; ?> Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="./CSS/bootstrap.min.css">
    <link rel="stylesheet" href="./CSS/all.min.css">
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

.btn-outline-warning a{
    color: #ffba00;
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
    
    .log {
        margin: 10px auto !important;
        display: block;
        color: #ffba00;
        border: #ffba00 2px solid;
    }
}



        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            display: flex;
            gap: 20px;
        }
        .class-info {
            width: 30%;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .materials {
            width: 70%;
        }
        .class-name {
            font-size: 1.8em;
            color: #0c3b2e;
            margin-bottom: 5px;
        }
        .class-grade {
            font-size: 1.3em;
            color: #6d9773;
            margin-bottom: 15px;
        }
        .student-list {
            margin-top: 20px;
            max-height: 400px;
            overflow-y: auto;
        }
        .student-item {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .post {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .post-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .post-author {
            font-weight: bold;
            color: #0c3b2e;
        }
        .post-date {
            color: #777;
        }
        .post-content {
            margin: 15px 0;
        }
        .post-attachment {
            margin: 10px 0;
        }
        .download-btn {
            color: #0c3b2e;
            text-decoration: none;
        }
        .download-btn:hover {
            text-decoration: underline;
        }
        .comments {
            margin-top: 15px;
            padding-left: 20px;
            border-left: 2px solid #eee;
        }
        .comment {
            background: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .comment-author {
            font-weight: bold;
        }
        .comment-date {
            color: #777;
            font-size: 0.9em;
        }
        .new-post-form {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        textarea {
            width: 100%;
            min-height: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .bton {
            padding: 8px 15px;
            background: #ffba00;
            color: #0c3b2e;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
        }
        .bton:hover {
            background:rgb(255, 196, 33);
            color: #0c3b2e;

        }
        .delete-btn {
            background: #dc3545;
            color: white;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .comments-container {
        max-height: 150px;
        overflow-y: auto;
        padding-right: 10px; /* Prevents content from touching scrollbar */
    }
    
    /* Custom scrollbar styling */
    .comments-container::-webkit-scrollbar {
        width: 8px;
    }
    
    .comments-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .comments-container::-webkit-scrollbar-thumb {
        background: #6d9773;
        border-radius: 4px;
    }
    
    .comments-container::-webkit-scrollbar-thumb:hover {
        background: #0c3b2e;
    }
    
    /* Make student list scrollable too */
    .student-list {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .student-list::-webkit-scrollbar {
        width: 8px;
    }
    
    .student-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .student-list::-webkit-scrollbar-thumb {
        background: #6d9773;
        border-radius: 4px;
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


    <div class="container">
        <!-- Left Side - Class Information -->
        <div class="class-info">
            <h1 class="class-name"><?php echo $grade . $class['ClassName']; ?></h1>
            <p class="class-grade">Grade <?php echo htmlspecialchars($grade); ?></p>
            
            <div class="class-meta">
                <p><strong>Teacher:</strong> <?php echo $FTeacher['TeacherName']; ?></p>
                <p><strong>Students:</strong> <?php echo $student_count; ?></p>
            </div>
            
            <div class="student-list">
                <h3>Student List</h3>
                 <?php while($student = mysqli_fetch_assoc($students_result)) { ?> 
                    <div class="student-item" style="display: flex; align-items: center; justify-content: space-between;">
                        <span>
                            <?php echo htmlspecialchars($student['StudentName']); ?>
                        </span>
                    </div>
                <?php } ?>
            </div>
            <!-- Assign Marks to All Students Button -->
            <a href="AssignMarks.php?class_id=<?php echo $class_id; ?>&grade=<?php echo $grade; ?>&subject_id=<?php echo $FTeacher['Subject']; ?>" 
               class="btn bton btn-success" style="margin:10px 0;">
                <i class="fas fa-plus"></i> Assign Marks to All Students
            </a>
        </div>
        
        <!-- Right Side - Materials and Posts -->
        
        
        <div class="materials">
            <!-- <div class="student-list">
                   <h3>Student List</h3>
                   <?php while($student = mysqli_fetch_assoc($students_result)) { ?>
                       <div class="student-item" style="display: flex; align-items: center; justify-content: space-between;">
                           <span>
                               <?php echo htmlspecialchars($student['StudentName']); ?>
                           </span>
                       </div>
                   <?php } ?>
               </div>
                 Assign Marks to All Students Button -->
            <!-- <a href="AssignMarks.php?class_id=<?php echo $class_id; ?>&grade=<?php echo $grade; ?>&subject_id=<?php echo $FTeacher['Subject']; ?>" 
               class="btn bton btn-success" style="margin:10px 0;">
                <i class="fas fa-plus"></i> Assign Marks to All Students
            </a>  -->

            <h2>Class Materials</h2>
            
            <!-- New Post Form -->
            <div class="new-post-form">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                    <textarea name="post_content" placeholder="Write your announcement..." required></textarea>

                <div class="input-group mb-3">
                    <input type="file" class="form-control" name="materiall" id="material_file">
                </div>
                    <!-- <div>
                        <input type="file" name="materiall" id="material_file">
                    </div> -->
                    <button type="submit" name="upload" class="btn bton">Post Material</button>
                </form>
            </div>
            
            <!-- Existing Materials/Posts -->
            <?php foreach ($materials_result as $material) { ?>
                <div class="post">
                    <div class="post-header">
                        <span class="post-author"><?php echo htmlspecialchars($material['TeacherName']); ?></span>
                        <!-- <span class="post-date"><?php echo date('M j, Y g:i a', strtotime($material['created_at'])); ?></span> -->
                        <a href="ClassDetails.php?class_id=<?php echo $class_id; ?>&grade=<?php echo $grade; ?>&delete_material=<?php echo $material['MaterialID']; ?>" 
                           class="btn delete-btn" 
                           onclick="return confirm('Are you sure you want to delete this post? All student submissions will also be deleted.')">
                           Delete
                        </a>
                    </div>
                    
                    <div class="post-content">
                        <?php echo nl2br(htmlspecialchars($material['body'])); ?>
                    </div>
                    
                    <?php if(!empty($material['Material'])) { ?>
                        <div class="post-attachment">
                            <a href="../Media/Uploads/<?php echo htmlspecialchars($material['Material']); ?>" 
                               class="download-btn" download>
                               <i class="fas fa-download"></i> Download Attachment
                            </a>
                        </div>
                    <?php } ?>
                    
                    <!-- Comments Section -->
                    <div class="comments">
    <div class="comments-container">
        <?php 
        // Get comments for this material
        $comments_query = "SELECT s.*, c.* FROM submitions c
                         JOIN students s ON c.StudentID = s.StudentID
                         WHERE c.MaterialID = '{$material['MaterialID']}'";
        $comments_result = mysqli_query($connect, $comments_query);
        
        if(mysqli_num_rows($comments_result) > 0) {
            while($comment = mysqli_fetch_assoc($comments_result)) { ?>
                <div class="comment">
                    <div class="comment-header">
                        <span class="comment-author"><?php echo htmlspecialchars($comment['StudentName']); ?></span>
                        <span class="comment-date"><?php echo date('M j, Y g:i a', strtotime($comment['SubmittedAt'] ?? 'now')); ?></span>
                    </div>
                    <div class="comment-content">
                        <?php echo nl2br(htmlspecialchars($comment['Comment'])); ?>
                    </div>
                    <?php if(!empty($comment['Media'])) { ?>
                        <div class="post-attachment">
                            <a href="../Media/submissions/<?php echo htmlspecialchars($comment['Media']); ?>" 
                               class="download-btn" download>
                               <i class="fas fa-download"></i> <?php echo htmlspecialchars($comment['Media']); ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            <?php }
        } else { ?>
            <div class="comment">
                <div class="comment-content">
                    No submissions or replies
                </div>
            </div>
        <?php } ?>
    </div>
        </div>
        </div>
    <?php }?>
</div>
<script>
function markAttendance(studentId, value) {
    // Send AJAX request to MarkAttendance.php
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "MarkAttendance.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
            alert(xhr.responseText);
        }
    };
    // You may need to get subject_id and grade_id from PHP variables
    xhr.send("student_id=" + studentId + 
             "&mark_value=" + value + 
             "&class_id=<?php echo $class_id; ?>" +
             "&grade=<?php echo $grade; ?>" +
             "&subject_id=<?php echo $class['SubjectID']; ?>" +
             "&grade_id=<?php echo $class['GradeID']; ?>");
}
</script>
</body>
</html>