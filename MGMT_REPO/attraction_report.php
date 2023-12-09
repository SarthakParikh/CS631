<!-- attraction_report.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attraction Report</title>
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
        
        // Function to get top attractions
        function getTopAttractions($conn, $startDate, $endDate)
        {
            $attractions = [];
            $sql = " SELECT RT.Name AS AttractionName, SUM(RE.Revenue) AS TotalRevenue 
            FROM revenue_event RE 
            JOIN revenue_type RT ON RE.RID = RT.RID 
            WHERE RT.RID BETWEEN 1 AND 6 AND RE.Date BETWEEN '$startDate' AND '$endDate' 
            GROUP BY RT.Name 
            ORDER BY TotalRevenue DESC 
            LIMIT 3;";


           
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $attractions[] = $row;
                }
            }

            return $attractions;
        }

        // Get start and end dates from user input (you can use a form to collect user input)
        $userInputStartDate = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-01');
        $userInputEndDate = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');

        // Get top attractions based on user input dates
        $topAttractions = getTopAttractions($conn, $userInputStartDate, $userInputEndDate);
    ?>

<nav>
      <a href="../index.html">Home</a>

      <a href="../mgmt_rep.html">Management and Reporting </a>

    </nav>
    <h2>Top Attractions Report</h2>

    <form method="post" action="">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" value="<?= $userInputStartDate; ?>">
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" value="<?= $userInputEndDate; ?>">
        <button type="submit">Generate Report</button>
    </form>

    <?php if (!empty($topAttractions)): ?>
        <table>
            <tr>
                <th>RevenueType</th>
                <th>AverageRevenue</th>
            </tr>
            <?php foreach ($topAttractions as $attraction): ?>
                <tr>
                    <td><?= $attraction['AttractionName']; ?></td>
                    <td><?= $attraction['TotalRevenue']; ?></td>
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
