<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbName = 'timetable_db';

$conn = new mysqli($host, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$department = $class = $semester = $days = $periods = '';
$subjects = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department = $_POST['department'];
    $class = $_POST['class'];
    $semester = $_POST['semester'];
    $days = intval($_POST['days']);
    $periods = intval($_POST['periods']);
    $subjects = explode(',', $_POST['subjects']);

    $timetableData = generateTimetable($days, $periods, $subjects);

    $timetableJSON = json_encode($timetableData);

    $query = "INSERT INTO timetable (department, class_name, semester, timetable_data) VALUES ('$department', '$class', '$semester', '$timetableJSON')";

    if ($conn->query($query) === TRUE) {
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

$conn->close();

function generateTimetable($days, $periods, $subjects) {
    $timetableData = array();
    $daysOfWeek = array("I", "II", "III", "IV", "V", "VI", "VII");

    shuffle($subjects);

    for ($i = 0; $i < $days; $i++) {
        $day = $daysOfWeek[$i];
        $timetableData[$day] = array();

        $minOccurrences = 2;
        $maxOccurrences = 3;
        $totalOccurrences = ($minOccurrences + $maxOccurrences) / 2 * $periods;

        $assignedSubjects = array();
        $remainingOccurrences = $totalOccurrences;

        for ($period = 1; $period <= $periods; $period++) {
            $availableSubjects = array_values(array_diff($subjects, $assignedSubjects));

            if ($remainingOccurrences > 0 && count($availableSubjects) > 0) {
                $randomSubject = $availableSubjects[array_rand($availableSubjects)];
                $timetableData[$day][$period] = $randomSubject;
                $assignedSubjects[] = $randomSubject;
                $remainingOccurrences--;
            } else {
                $timetableData[$day][$period] = $availableSubjects[array_rand($availableSubjects)];
            }
        }
    }

    return $timetableData;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Timetable</title>
    <style>
        body {
            font-family: Helvectica, sans-serif; 
            margin: 0;
            padding: 0;
            background: #f2f2f2;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background: linear-gradient(to right, #0053ba, #00addb);
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            color: #fff;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        .btn {
            background: #fff;
            color: #0066cc;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease-in-out;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0066cc;
            color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        }

        .btn:focus {
            outline: none;
            background-color: #0066cc;
            color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        }

        ::placeholder {
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Timetable Scheduled Successfully!</h1>
        <button class="btn" onclick="window.location.href='view_timetable.php'">View Timetable</button>
    </div>
</body>
</html>