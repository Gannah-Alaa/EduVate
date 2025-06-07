<?php
include '../connection.php';
// Remove duplicate session_start() if already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['TeacherID'])) {
    header("Location: login.php");
    exit;
}

$class_id = $_GET['class_id'] ?? '';
$grade = $_GET['grade'] ?? '';
$student_id = $_GET['student_id'] ?? ''; // Optional: for single-student mode

// Accept subject_id from GET if present
$subject_id = $_GET['subject_id'] ?? '';

// Fetch class info
$class_query = "SELECT * FROM class WHERE ClassID='$class_id'";
$class_result = mysqli_query($connect, $class_query);
$class = mysqli_fetch_assoc($class_result);

// Use subject_id from GET if provided, else from class
if (empty($subject_id) && isset($class['SubjectID'])) {
    $subject_id = $class['SubjectID'];
}
$grade_id = isset($class['GradeID']) ? $class['GradeID'] : '';

// Fetch students
if ($student_id) {
    $students_query = "SELECT * FROM students WHERE StudentID='$student_id'";
} else {
    $students_query = "SELECT * FROM students WHERE Class='$class_id' AND Grade='$grade' ORDER BY StudentName";
}
$students_result = mysqli_query($connect, $students_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_type'])) {
    $mark_type = mysqli_real_escape_string($connect, $_POST['mark_type']);
    $semester = intval($_POST['semester']);

    // Check for valid subject_id before inserting
    if (empty($subject_id)) {
        echo "<script>alert('Error: SubjectID is missing or invalid for this class. Cannot assign marks.');window.location='ClassDetails.php?class_id=$class_id&grade=$grade';</script>";
        exit;
    }

    // Optional: Check if subject_id exists in subjects table to avoid FK error
    $subject_check = mysqli_query($connect, "SELECT 1 FROM subjects WHERE SubjectID='$subject_id' LIMIT 1");
    if (mysqli_num_rows($subject_check) == 0) {
        echo "<script>alert('Error: SubjectID ($subject_id) does not exist in subjects table. Cannot assign marks.');window.location='ClassDetails.php?class_id=$class_id&grade=$grade';</script>";
        exit;
    }

    // Include mailer for notifications
    include_once 'mail.php';

    // Notification helper functions (inline for this file)
    function sendParentNotification($parentEmail, $studentName, $mark, $average, $type, $markType) {
        global $mail;
        $mail->clearAddresses();
        $mail->setFrom('notyourbusiness.960@gmail.com', 'Eduvate');
        $mail->addAddress($parentEmail);
        $mail->isHTML(true);

        if ($type === 'low') {
            $mail->Subject = "Alert: Low Mark for $studentName";
            $mail->Body = "Dear Parent,<br>Your child <b>$studentName</b> has received a $markType mark of <b>$mark</b>, which is more than 40% below their average (<b>$average</b>). Please follow up with your child.";
        } elseif ($type === 'high') {
            $mail->Subject = "Congratulations: Excellent Mark for $studentName";
            $mail->Body = "Dear Parent,<br>Your child <b>$studentName</b> has achieved a full $markType mark of <b>$mark</b>! Congratulations!";
        }
        $mail->send();
    }
    function getFullMark($markType) {
        switch (strtolower($markType)) {
            case 'quiz':
            case 'quizzes':
                return 10;
            case 'midterm':
                return 50;
            case 'final':
                return 100;
            default:
                return 100;
        }
    }

    foreach ($_POST['marks'] as $sid => $mark_value) {
        $sid = intval($sid);
        $mark_value = floatval($mark_value);
        if ($mark_value !== '' && is_numeric($mark_value)) {
            $query = "INSERT INTO marks (SubjectID, StudentID, MarkType, MarkValue, Semester, GradeID)
                      VALUES ('$subject_id', '$sid', '$mark_type', '$mark_value', '$semester', '$grade')";
            mysqli_query($connect, $query);

            // --- Notification logic after mark insert ---
            // Only send mail for Quiz, Midterm, or Final
            if (in_array(strtolower($mark_type), ['quiz', 'midterm', 'final'])) {
                // Get parent email and student name
                $student_query = "SELECT s.StudentName, p.ParentEmail FROM students s 
                JOIN family f ON s.StudentID = f.StudentID 
                JOIN parents p ON f.ParentID = p.ParentID
                WHERE s.StudentID='$sid' LIMIT 1";
                $student_result = mysqli_query($connect, $student_query);
                if ($student_row = mysqli_fetch_assoc($student_result)) {
                    $student_name = $student_row['StudentName'];
                    $parent_email = $student_row['ParentEmail'];

                    // Calculate average for this MarkType
                    $avg_query = "SELECT AVG(MarkValue) as avg_mark FROM marks WHERE StudentID='$sid' AND MarkType='$mark_type'";
                    $avg_result = mysqli_query($connect, $avg_query);
                    $avg_row = mysqli_fetch_assoc($avg_result);
                    $average = floatval($avg_row['avg_mark']);

                    $full_mark = getFullMark($mark_type);

                    // Check for 40% below average (and average > 0)
                    if ($average > 0 && $mark_value < ($average * 0.6)) {
                        sendParentNotification($parent_email, $student_name, $mark_value, $average, 'low', $mark_type);
                    }

                    // Check for full mark or A+
                    if ($mark_value >= $full_mark) {
                        sendParentNotification($parent_email, $student_name, $mark_value, $average, 'high', $mark_type);
                    }
                }
            }
            // --- End notification logic ---
        }
    }
    echo "<script>alert('Marks assigned successfully!');window.location='ClassDetails.php?class_id=$class_id&grade=$grade';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Marks</title>
    <link rel="stylesheet" href="./CSS/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body { background: #f5f5f5; font-family: Arial, sans-serif; margin: 20px; }
        .container { background: #fff; padding: 30px; border-radius: 8px; max-width: 700px; margin: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.08);}
        h2 { color: #0c3b2e; }
        .form-group { margin-bottom: 18px; }
        .btn { background: #0c3b2e; color: #fff; }
        .btn:hover { background: #6d9773; }
        table { width: 100%; margin-top: 20px; }
        th, td { padding: 8px; text-align: left; }
        th { background: #f1f1f1; }
    </style>
</head>
<body>
<div class="container">
    <h2>Assign Marks for <?php echo htmlspecialchars($class['ClassName'] ?? ''); ?> (Grade <?php echo htmlspecialchars($grade); ?>)</h2>
    <form method="post">
        <div class="form-group">
            <label for="mark_type">Mark Type:</label>
            <select name="mark_type" id="mark_type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="Attendance">Attendance</option>
                <option value="Quiz">Quizzes</option>
                <option value="Midterm">Midterm</option>
                <option value="Final">Final</option>
                <option value="Classwork">Classwork</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="semester">Semester:</label>
            <select name="semester" id="semester" class="form-control" required>
                <option value="1">Semester 1</option>
                <option value="2">Semester 2</option>
            </select>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Mark Value</th>
                </tr>
            </thead>
            <tbody>
            <?php while($student = mysqli_fetch_assoc($students_result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['StudentName']); ?></td>
                    <td>
                        <input required type="number" step="0.01" name="marks[<?php echo $student['StudentID']; ?>]" class="form-control" placeholder="Enter mark">
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <button type="submit" class="btn">Submit Marks</button>
        <a href="ClassDetails.php?class_id=<?php echo $class_id; ?>&grade=<?php echo $grade; ?>" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
