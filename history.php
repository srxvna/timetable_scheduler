<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Time Table History</title>
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
            max-width: 800px;
            padding: 20px;
            background: linear-gradient(to right, #0053ba, #00addb);
            border-radius: 20px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: #fff;
        }

        th {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: #0053ba;
            background-color: #f9f9f9;
            font-weight: bold;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        a:hover {
            color: black;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Time Table History</h1>
        
        <?php
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $dbName = 'timetable_db';

            $conn = new mysqli($host, $username, $password, $dbName);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $id = $_GET['id'];
                
                $deleteQuery = "DELETE FROM timetable WHERE id = $id";
                
                if ($conn->query($deleteQuery) === TRUE) {
                    // Redirect back to the timetable history page
                    header("Location: history.php");
                    exit();
                } else {
                    echo "Error deleting entry: " . $conn->error;
                }
            }

            $query = "SELECT * FROM timetable ORDER BY id DESC";
            $result = $conn->query($query);

            if ($result->num_rows > 0) :
        ?>
        
        <table>
            <tr>
                <th>Department</th>
                <th>Class</th>
                <th>Semester</th>
                <th>View</th>
                <th>Delete</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['department']; ?></td>
                <td><?php echo $row['class_name']; ?></td>
                <td><?php echo $row['semester']; ?></td>
                <td><a href="view_timetable.php?id=<?php echo $row['id']; ?>">View</a></td>
                <td><a href="history.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this timetable?')">Delete</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else : ?>
        <p>No time table history available.</p>
        <?php endif; ?>

        <?php
        $conn->close();
        ?>
    </div>
</body>
</html>
