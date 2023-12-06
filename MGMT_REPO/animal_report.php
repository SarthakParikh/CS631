<!-- index.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Report</title>
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
   // Database connection parameters
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "turtleback"; // Replace with your MySQL database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

        // Function to generate animal report
        function generateAnimalReport($conn)
        {
            $report = [];
            $sql = "SELECT S.Name AS SpeciesName, A.Status, CAST(COUNT(*)/2 as int) AS TotalAnimals, SUM(S.Food_Cost) AS TotalFoodCost, SUM(CASE WHEN E.VetFl = 1 THEN HR.Rate* 40 * 4 ELSE 0 END) AS VetTotalSalary, SUM(CASE WHEN E.ACTSFl = 1 THEN HR.Rate * 40 * 4 ELSE 0 END) AS ActsTotalSalary, SUM(CASE WHEN E.VetFl = 1 THEN HR.Rate * 40 * 4 ELSE 0 END + CASE WHEN E.ACTSFl = 1 THEN HR.Rate * 40 * 4 ELSE 0 END) as TotalSalary FROM Animal A JOIN Species S ON A.SID = S.SID JOIN Cares_For CF ON A.SID = CF.SID JOIN Employee E ON CF.ESSN = E.SSN JOIN Hourly_Rate HR ON E.HRID = HR.HRID WHERE E.VetFl = 1 OR E.ACTSFl = 1 GROUP BY S.Name, A.Status ORDER BY S.Name, A.Status;"
            ;
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $report[] = $row;
                }
            }

            return $report;
        }

        // Generate animal report
        $animalData = generateAnimalReport($conn);
    ?>




    <h2>Animal Report</h2>

    <nav>
      <a href="../index.html">Home</a>

      <a href="../mgmt_rep.html">Animal Report </a>

    </nav>


    <?php if (!empty($animalData)): ?>
        <table>
            <tr>
                <th>Species</th>
                <th>Status</th>
                <th>Food Cost</th>
                <th>Vet Cost</th>
                <th>Specialist Cost</th>
                <th>total Cost</th>

            </tr>
            <?php foreach ($animalData as $entry): ?>
                <tr>
                    <td><?= $entry['SpeciesName']; ?></td>
                    <td><?= $entry['Status']; ?></td>
                    <td><?= $entry['TotalFoodCost']; ?></td>
                    <td><?= $entry['VetTotalSalary']; ?></td>
                    <td><?= $entry['ActsTotalSalary']; ?></td>
                    <td><?= $entry['TotalSalary']; ?></td>

                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No animal data available.</p>
    <?php endif; ?>

    <?php
        // Close the database connection
        $conn->close();
    ?>

</body>
</html>
