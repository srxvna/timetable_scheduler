<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Time Table</title>
    <style>
        body {
            font-family: Helvectica, sans-serif; 
            margin: 0;
            padding: 0;
            background: #f2f2f2;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background: linear-gradient(to right, #0053ba, #00addb);
            border-radius: 10px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
        }

        h1 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 10px;
            font-weight: normal;
            color: #eee;
        }

        table {
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px 20px;
            text-align: center;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        th:first-child {
            text-align: left;
        }

        td.editable {
            cursor: pointer;
        }

        @media print {
            body {
                background: none;
            }

            .container {
                box-shadow: none;
                padding: 0;
                width: 100%;
            }

            h1, .btn, p {
                display: none;
            }

            table {
                margin: 0 auto;
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                border: 1px solid #000;
            }

            .print-info {
                display: block;
                text-align: center;
                font-size: 14px;
                margin-bottom: 10px;
            }
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 5px 10px;
            }
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

    </style>
</head>
<body>
    <?php
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbName = 'timetable_db';

    $conn = new mysqli($host, $username, $password, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM timetable ORDER BY id DESC LIMIT 1";
    $result = $conn->query($query);

    $department = $class = $semester = "N/A";
    $timetableData = array(
        'I' => array(),
        'II' => array(),
        'III' => array(),
        'IV' => array(),
        'V' => array(),
        'VI' => array(),
        'VII' => array(),

    );

    $subjects = array();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $department = $row['department'];
        $class = $row['class_name'];
        $semester = $row['semester'];
        $timetableData = json_decode($row['timetable_data'], true);
        $subjects = getSubjectsFromTimetableData($timetableData);
    }

    $conn->close();

    function getSubjectsFromTimetableData($timetableData) {
        $subjects = array();
        foreach ($timetableData as $day => $periods) {
            foreach ($periods as $subject) {
                if (!in_array($subject, $subjects)) {
                    $subjects[] = $subject;
                }
            }
        }
        return $subjects;
    }
    ?>

    <div class="container">
        <h1>Time Table</h1>
        <div class="print-info">
                <h3>Department: <?php echo $department; ?><br>Class: <?php echo $class; ?><br>Semester: <?php echo $semester; ?></h3>
        </div>
    
        <?php if (!empty($timetableData) && is_array($timetableData)) : ?>
            <table>
                <tr>
                    <th>Day Order/Period</th>
                    <?php foreach ($timetableData['I'] as $period => $subject) : ?>
                        <th>Period <?php echo $period; ?></th>
                    <?php endforeach; ?>
                </tr>
                
                <?php foreach ($timetableData as $day => $periods) : ?>
                    <tr>
                        <td><?php echo $day; ?></td>
                        <?php foreach ($periods as $period => $subject) : ?>
                            <td class="editable" data-day="<?php echo $day; ?>" data-period="<?php echo $period; ?>" contenteditable="true">
                                <?php echo $subject; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
            
            <div style="text-align: center; margin-top: 20px;">
                <a class="btn" onclick="window.print()">Print Time Table</a>      
            </div>
            
        <?php else : ?>
            <p>No timetable data available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
