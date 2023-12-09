<!-- revenue_report.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Report</title>
    <style>
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
        

        // Function to generate revenue report
        function generateRevenueReport($conn, $date)
        {
            $report = [];
            $sql = "SELECT
            CASE
                WHEN rt.name IS NOT NULL THEN rt.name  -- Attraction Name
                WHEN c.Product IS NOT NULL THEN c.Product  -- Concession Name
                WHEN za.RID IS NOT NULL THEN 'Zoo Admission'  -- Zoo Admission
                ELSE 'Unknown'
            END AS Revenue_Source,
            re.Date AS Date,
            re.RID AS Attendance_ID,
            CASE
                WHEN rt.name IS NOT NULL THEN 'Attraction'
                WHEN c.Product IS NOT NULL THEN 'Concession'
                WHEN za.RID IS NOT NULL THEN 'Zoo Admission'
                ELSE 'Unknown'
            END AS Source_Type,
            re.ticketsold AS Ticket_Sold,
            re.revenue AS Revenue
        FROM
            revenue_event re
        LEFT JOIN
            revenue_type rt ON re.RID = rt.RID
        LEFT JOIN
            concession c ON re.RID = c.RID
        LEFT JOIN
            zoo_admission za ON re.RID = za.RID
        WHERE
            re.Date = '2023-10-01';";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $report[] = $row;
                }
            }

            return $report;
        }

    
        $userInputDate = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');

     
        $revenueData = generateRevenueReport($conn, $userInputDate);
    ?>

    
<nav>
      <a href="../index.html">Home</a>

      <a href="../mgmt_rep.html">Management and Reporting </a>

    </nav>

    <h2>Revenue Report</h2>

    <form method="post" action="">
        <label for="date">Select Date:</label>
        <input type="date" name="date" value="<?= $userInputDate; ?>">
        <button type="submit">Generate Report</button>
    </form>

    <?php if (!empty($revenueData)): ?>
        <table>
            <tr>
                <th>Source</th>
                <th>Revenue</th>
            </tr>
            <?php foreach ($revenueData as $entry): ?>
                <tr>
                    <td><?= $entry['Revenue_Source']; ?></td>
                    <td><?= $entry['Revenue']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No revenue data available for the selected date.</p>
    <?php endif; ?>

    <?php
        // Close the database connection
        $conn->close();
    ?>

</body>
</html>
