<?php
include '../connection.php';
include '../Teacher/mail.php'; // adjust path if needed

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

// Define full mark for each MarkType
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
            return 100; // fallback for other types
    }
}

// Optionally, add a simple HTML interface to trigger the process
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notify'])) {
    // Get all students and their parents' emails
    $students_query = "
        SELECT s.StudentID, s.StudentName, f.ParentEmail
        FROM students s
        JOIN family f ON s.FamilyID = f.FamilyID
    ";
    $students_result = mysqli_query($connect, $students_query);

    while ($student = mysqli_fetch_assoc($students_result)) {
        $student_id = $student['StudentID'];
        $student_name = $student['StudentName'];
        $parent_email = $student['ParentEmail'];

        // Get the latest mark and its type for the student
        $latest_query = "SELECT MarkValue, MarkType FROM marks WHERE StudentID='$student_id' ORDER BY MarkID DESC LIMIT 1";
        $latest_result = mysqli_query($connect, $latest_query);
        if ($latest_row = mysqli_fetch_assoc($latest_result)) {
            $latest_mark = floatval($latest_row['MarkValue']);
            $mark_type = $latest_row['MarkType'];

            // Calculate average for this MarkType
            $avg_query = "SELECT AVG(MarkValue) as avg_mark FROM marks WHERE StudentID='$student_id' AND MarkType='$mark_type'";
            $avg_result = mysqli_query($connect, $avg_query);
            $avg_row = mysqli_fetch_assoc($avg_result);
            $average = floatval($avg_row['avg_mark']);

            $full_mark = getFullMark($mark_type);

            // Check for 40% below average (and average > 0)
            if ($average > 0 && $latest_mark < ($average * 0.6)) {
                sendParentNotification($parent_email, $student_name, $latest_mark, $average, 'low', $mark_type);
            }

            // Check for full mark or A+
            if ($latest_mark >= $full_mark) {
                sendParentNotification($parent_email, $student_name, $latest_mark, $average, 'high', $mark_type);
            }
        }
    }
    echo "<p>Notifications sent (if any matched the criteria).</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notify Parents</title>
</head>
<body>
    <h2>Send Parent Notifications</h2>
    <form method="post">
        <button type="submit" name="notify">Send Notifications</button>
    </form>
</body>
</html>
