<!-- best_days_report.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Days Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-top: 20px;
        }

        label {
            margin-right: 10px;
        }

        button {
            padding: 8px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <?php
       $servername = "localhost";
       $username = "root";
       $password = "";
       $dbname = "turtleback"; 
       
       $conn = new mysqli($servername, $username, $password, $dbname);
       
       if ($conn->connect_error) {
           die("Connection failed: " . $conn->connect_error);
       }
       
       function sanitize($conn, $data) {
           return mysqli_real_escape_string($conn, trim($data));
       }
       
        // Function to get best days
        function getBestDays($conn, $month, $year)
        {

    
                // $dateTime = new DateTime($month);
                //  $month1 = $dateTime->format('m');
            echo $month;
            $bestDays = [];
            $sql = "SELECT DATE(re.Date) AS Revenue_Date, SUM(re.Revenue) AS Total_Revenue FROM revenue_event re WHERE MONTH(re.Date) = '$month' AND YEAR(re.Date) = '$year' GROUP BY Revenue_Date ORDER BY Total_Revenue DESC LIMIT 5;";
           
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $bestDays[] = $row;
                }
            }

            return $bestDays;
        }

        // Get month and year from user input (you can use a form to collect user input)
        $userInputMonth = isset($_POST['month']) ? $_POST['month'] : date('m');
        $userInputYear = isset($_POST['year']) ? $_POST['year'] : date('Y');

        // Get best days based on user input month and year
        $bestDays = getBestDays($conn, $userInputMonth, $userInputYear);
    ?>

    
<nav>
      <a href="../index.html">Home</a>

      <a href="../mgmt_rep.html">Management and Reporting </a>

    </nav>

    <h2>Best Days Report</h2>

    <form method="post" action="">
        <label for="month">Select Month:</label>
        <input type="number" name="month" min="1" max="12" required>
        <label for="year">Select Year:</label>
        <input type="number" name="year" value="<?= $userInputYear; ?>">
        <button type="submit">Generate Report</button>
    </form>

    <?php if (!empty($bestDays)): ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Revenue</th>
            </tr>
            <?php foreach ($bestDays as $day): ?>
                <tr>
                    <td><?= $day['Revenue_Date']; ?></td>
                    <td><?= $day['Total_Revenue']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No data available for the selected month and year.</p>
    <?php endif; ?>

    <?php
        // Close the database connection
        $conn->close();
    ?>

</body>
</html>
