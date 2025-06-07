<?php 
include("../connection.php");

$StudentID = 1;
$MarksArray = [];

if (!isset($_SESSION['StudentID'])) {
    if (isset($_GET['SubjectId']) && isset($_GET['GradeID'])) {
        $SubjectId = $_GET['SubjectId'];

        $selectMarks = "SELECT Sum(M.MarkValue) as Count, M.MarkType FROM marks M 
        JOIN subjects S ON S.SubjectID = M.SubjectID
        WHERE StudentID = $StudentID AND M.SubjectID = $SubjectId
        GROUP BY MarkType";
        
        $RunSelectMarks = mysqli_query($connect, $selectMarks);

        while ($row = mysqli_fetch_assoc($RunSelectMarks)) {
            $MarksArray[] = [
                'MarkType' => $row['MarkType'],
                'Count' => $row['Count']
            ];
        }
    }
}

// Convert the marks array to JSON format for use in JavaScript
$marksJson = json_encode($MarksArray);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks Line Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<canvas id="myLineChart" ></canvas>

<script>
    // Parse the PHP JSON data into a JavaScript object
    const marksData = <?php echo $marksJson; ?>;

    // Prepare labels and data for the chart
    const labels = marksData.map(item => item.MarkType);
    const counts = marksData.map(item => item.Count);

    // Create the line chart
    const ctx = document.getElementById('myLineChart').getContext('2d');
    const myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Marks',
                data: counts,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>
