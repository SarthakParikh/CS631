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
        $servername = "your_server_name"; // Replace with your MySQL server name
        $username = "your_username"; // Replace with your MySQL username
        $password = "your_password"; // Replace with your MySQL password
        $database = "your_database"; // Replace with your MySQL database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Function to generate animal report
        function generateAnimalReport($conn)
        {
            $report = [];
            $sql = "SELECT species, status, food_cost, vet_cost, specialist_cost FROM animals";
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

    <?php if (!empty($animalData)): ?>
        <table>
            <tr>
                <th>Species</th>
                <th>Status</th>
                <th>Food Cost</th>
                <th>Vet Cost</th>
                <th>Specialist Cost</th>
            </tr>
            <?php foreach ($animalData as $entry): ?>
                <tr>
                    <td><?= $entry['species']; ?></td>
                    <td><?= $entry['status']; ?></td>
                    <td><?= $entry['food_cost']; ?></td>
                    <td><?= $entry['vet_cost']; ?></td>
                    <td><?= $entry['specialist_cost']; ?></td>
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
