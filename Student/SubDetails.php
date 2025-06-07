<?php
include '../connection.php';

// Get parameters from URL
$subjectId = isset($_GET['SubjectID']) ? $_GET['SubjectID'] : 0;
$teacherId = isset($_GET['TeacherID']) ? $_GET['TeacherID'] : 0;
$classId = isset($_GET['ClassID']) ? $_GET['ClassID'] : 0;
$gradeId = isset($_GET['GradeID']) ? $_GET['GradeID'] : 0;
$studentId = $_SESSION['StudentID']; // Assuming student is logged in

// Fetch subject, teacher, and class details
$detailsQuery = "SELECT *
                 FROM subjects s
                 JOIN teachers t ON t.TeacherID = $teacherId
                 JOIN class c ON c.ClassID = $classId
                 JOIN grades g ON g.GradeID = $gradeId
                 WHERE s.SubjectID = $subjectId";
$detailsResult = mysqli_query($connect, $detailsQuery);
$details = mysqli_fetch_assoc($detailsResult);

// Fetch all materials for this subject/teacher/class
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
    <title><?php echo $details['SubjectName'] ?> - Materials</title>
    <style>
        .material-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .material-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .material-title {
            font-size: 1.5em;
            margin-top: 0;
            color: #2c3e50;
        }
        .material-meta {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
        .material-body {
            line-height: 1.6;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 15px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .submission-form {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .submission-list {
            margin-top: 20px;
        }
        .submission-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .comments-container {
        margin-top: 30px;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }
    
    .comment-card {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #4CAF50;
    }
    
    .comment-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px dashed #ddd;
    }
    
    .comment-date {
        color: #666;
        font-size: 0.85em;
    }
    
    .comment-content h4, 
    .comment-file h4 {
        margin: 10px 0 5px 0;
        color: #333;
        font-size: 1em;
    }
    
    .comment-content p {
        margin: 0;
        color: #444;
        line-height: 1.5;
    }
    
    .comment-file a {
        display: inline-block;
        padding: 5px 10px;
        background: #e3f2fd;
        border-radius: 4px;
        color: #1976d2;
        text-decoration: none;
    }
    
    .comment-file a:hover {
        background: #bbdefb;
    }
    
    .comment-file i {
        margin-right: 5px;
    }
    </style>
</head>
<body>
    <div class="material-container">
        <a href="profile.php" class="back-btn">← Back to Profile</a>
        
        <h1><?php echo $details['SubjectName'] ?>
        <?php if(!empty($details['Ebooks'])) { ?>
            <a href="../uploads/<?php echo $details['Ebooks'] ?>" download>Download E-Books</a>
        <?php } ?>
        </h1>
        <h3>Teacher: <?php echo $details['TeacherName'] ?></h3>
        <h3>Class: <?php echo $gradeId." ". $details['ClassName'] ?></h3>
        <h3><a href="subMarks.php">View Marks</a></h3>
        
        <h2>Uploaded Materials</h2>
        
        <?php if (mysqli_num_rows($materialsResult) > 0) { ?>
            <?php foreach ($materialsResult as $material) { ?>
                <div class="material-card">
                    <h3 class="material-title"><?php echo $material['Title'] ?></h3>
                    <div class="material-body">
                        <?php echo nl2br($material['body']) ?>
                    </div>
                    <?php if (!empty($material['Material'])) { ?>
                        <p>
                            <a href="../uploads/<?php echo $material['Material'] ?>" download>
                                Download Attachment
                            </a>
                        </p>
                    <?php } ?>
                    
                    <!-- Submission Form -->
                    <div class="submission-form">
                        <h4>Submit Your Work</h4>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="material_id" value="<?php echo $material['MaterialID'] ?>">
                            <div>
                                <label for="comment">Comment:</label>
                                <textarea name="comment" id="comment" rows="3" style="width: 100%" ></textarea>
                            </div>
                            <div>
                                <label for="media">Upload File:</label>
                                <input type="file" name="media" id="media">
                            </div>
                            <button type="submit" name="submitcomment" style="margin-top: 10px;">Submit</button>
                        </form>
                    </div>
                    
                    <!-- Previous Submissions -->
                    <div class="submission-list">
                        <h4>Your Submissions</h4>
                        <?php
                        $submissionsQuery = "SELECT * FROM submitions 
                                           WHERE MaterialID = {$material['MaterialID']} 
                                           AND StudentID = $studentId
                                           ORDER BY SubmitID DESC";
                        $runSubmit = mysqli_query($connect, $submissionsQuery);
                        $count=mysqli_num_rows($runSubmit);
                        
                        if ($count > 0) { ?>
                            <div class="comments-container">
                                <?php foreach($runSubmit as $comm) { ?>
                                    <div class="comment-card">
                                        
                                        <?php if (!empty($comm['Comment'])) { ?>
                                            <div class="comment-content">
                                                <h4>Comment:</h4>
                                                <p><?php echo nl2br(htmlspecialchars($comm['Comment'])); ?></p>
                                            </div>
                                        <?php } ?>
                                        
                                        <?php if (!empty($comm['Media'])) { ?>
                                            <div class="comment-file">
                                                <h4>Attachment:</h4>
                                                <a href="../Media/submissions/<?php echo htmlspecialchars($comm['Media']); ?>" 
                                                   download="<?php echo htmlspecialchars($comm['Media']); ?>">
                                                   <i class="fas fa-file-download"></i> <?php echo htmlspecialchars($comm['Media']); ?>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php }





                         else {
                            echo '<p>No submissions yet.</p>';
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No materials have been uploaded yet for this subject.</p>
        <?php } ?>
    </div>
</body>
</html>
