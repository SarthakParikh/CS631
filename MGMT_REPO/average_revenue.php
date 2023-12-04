<!-- average_revenue.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Average Revenue Report</title>
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
    </style>
</head>
<body>

    <?php
        // Database connection parameters
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
        
        // Function to compute average revenue
        function computeAverageRevenue($conn, $startDate, $endDate)
        {
            $averageRevenue = [];
            $sql = "SELECT
            rt.name AS Revenue_Source,
            AVG(re.revenue) AS Average_Revenue
        FROM
            revenue_event re
        LEFT JOIN
            revenue_type rt ON re.RID = rt.RID
        WHERE
            re.Date BETWEEN '$startDate' AND '$endDate'
        GROUP BY
            Revenue_Source
        ORDER BY
            Average_Revenue DESC;";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $averageRevenue[] = $row;
                }
            }

            return $averageRevenue;
        }

        // Get start and end dates from user input (you can use a form to collect user input)
        $userInputStartDate = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-01');
        $userInputEndDate = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');

        // Compute average revenue based on user input dates
        $averageRevenue = computeAverageRevenue($conn, $userInputStartDate, $userInputEndDate);
    ?>

    <h2>Average Revenue Report</h2>

    <form method="post" action="">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" value="<?= $userInputStartDate; ?>">
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" value="<?= $userInputEndDate; ?>">
        <button type="submit">Generate Report</button>
    </form>

    <?php if (!empty($averageRevenue)): ?>
        <table>
            <tr>
                <th>Attraction</th>
                <th>Average Revenue</th>
            </tr>
            <?php foreach ($averageRevenue as $row): ?>
                <tr>
                    <td><?= $row['Revenue_Source']; ?></td>
                    <td><?= $row['Average_Revenue']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No data available for the selected date range.</p>
    <?php endif; ?>

    <?php
        // Close the database connection
        $conn->close();
    ?>

</body>
</html>
