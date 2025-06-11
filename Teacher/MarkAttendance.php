<?php
// useless page
include '../connection.php';
session_start();

if (!isset($_SESSION['TeacherID'])) {
    echo "Unauthorized";
    exit;
}

$student_id = $_POST['student_id'] ?? '';
$mark_value = $_POST['mark_value'] ?? '';
$subject_id = $_POST['subject_id'] ?? '';
$grade_id = $_POST['grade_id'] ?? '';

if (!$student_id || $mark_value === '' || !$subject_id || !$grade_id) {
    echo "Missing data";
    exit;
}

// Check if attendance already marked for today (optional, can be removed if not needed)
$date = date('Y-m-d');
$check_query = "SELECT * FROM marks WHERE StudentID='$student_id' AND SubjectID='$subject_id' AND MarkType='Attendance' AND DATE(CreatedAt)='$date'";
$check_result = mysqli_query($connect, $check_query);
if (mysqli_num_rows($check_result) > 0) {
    echo "Attendance already marked today.";
    exit;
}

$query = "INSERT INTO marks (SubjectID, StudentID, MarkType, MarkValue, Semester, GradeID, CreatedAt)
          VALUES ('$subject_id', '$student_id', 'Attendance', '$mark_value', 1, '$grade_id', NOW())";
if (mysqli_query($connect, $query)) {
    echo $mark_value == 1 ? "Marked present." : "Marked absent.";
} else {
    echo "Error: " . mysqli_error($connect);
}
