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
            // $sql = "SELECT S.Name AS SpeciesName, A.Status, CAST(COUNT(*)/2 as int) AS TotalAnimals, SUM(S.Food_Cost) AS TotalFoodCost, SUM(CASE WHEN E.VetFl = 1 THEN HR.Rate* 40 * 4 ELSE 0 END) AS VetTotalSalary, SUM(CASE WHEN E.ACTSFl = 1 THEN HR.Rate * 40 * 4 ELSE 0 END) AS ActsTotalSalary, SUM(CASE WHEN E.VetFl = 1 THEN HR.Rate * 40 * 4 ELSE 0 END + CASE WHEN E.ACTSFl = 1 THEN HR.Rate * 40 * 4 ELSE 0 END) as TotalSalary FROM Animal A JOIN Species S ON A.SID = S.SID JOIN Cares_For CF ON A.SID = CF.SID JOIN Employee E ON CF.ESSN = E.SSN JOIN Hourly_Rate HR ON E.HRID = HR.HRID WHERE E.VetFl = 1 OR E.ACTSFl = 1 GROUP BY S.Name, A.Status ORDER BY S.Name, A.Status;"
            // ;
//             COUNT(A.AID/2) AS TotalAnimals,

        //     $sql = "SELECT
        //     S.Name AS SpeciesName,
        //     CAST(COUNT(A.AID)/2 as int) AS TotalAnimals,
        //     SUM(CASE WHEN A.Status = 'Healthy' THEN 1 ELSE 0 END) AS Healthy,
        //     CAST(SUM(CASE WHEN A.Status = 'Under Medical Care' THEN 1 ELSE 0 END)/2 as int) AS Sick,
        //     cast(SUM(CASE WHEN A.Status = 'new born' THEN 1 ELSE 0 END)/2 as int) AS new_born,
        //     cast(SUM(CASE WHEN A.Status = 'Retired' THEN 1 ELSE 0 END)/2 as int) AS Retired,
        //     cast(SUM(CASE WHEN A.Status = 'Maternal leave' THEN 1 ELSE 0 END)/2 as int) AS Maternal_leave,
        //     SUM(S.Food_Cost) AS TotalFoodCost,
        //     SUM(CASE WHEN E.VetFl = 1 THEN HR.Rate * 40 * 4 ELSE 0 END) AS TotalVetCost,  -- Assuming 4 weeks in a month and 40 hours work week
        //     SUM(CASE WHEN E.ACTSFl = 1 THEN HR.Rate * 40 * 4 ELSE 0 END) AS TotalActsCost,
        //     SUM(CASE WHEN E.VetFl = 1 THEN HR.Rate * 40 * 4 ELSE 0 END) + SUM(CASE WHEN E.ACTSFl = 1 THEN HR.Rate * 40 * 4 ELSE 0 END) AS TotalCost
        // FROM
        //     Animal A
        // JOIN
        //     Species S ON A.SID = S.SID
        // LEFT JOIN
        //     Cares_For CF ON A.SID = CF.SID
        // LEFT JOIN
        //     Employee E ON CF.ESSN = E.SSN
        // LEFT JOIN
        //     Hourly_Rate HR ON E.HRID = HR.HRID
        // GROUP BY
        //     S.SID, S.Name;";



$sql = "SELECT
S.Name AS SpeciesName,
AnimalCount.TotalAnimals,
AnimalCount.Healthy,
AnimalCount.UnderMedicalCare,
AnimalCount.NewBorn,
AnimalCount.MaternalLeave,
AnimalCount.Retired,
(S.Food_Cost * AnimalCount.TotalAnimals) AS TotalFoodCost,
VetCount.TotalVetCost,
ActsCount.TotalActsCost,
VetCount.TotalVetCost + ActsCount.TotalActsCost AS TotalCost
FROM
Species S
LEFT JOIN (
SELECT
    SID,
    COUNT(AID) AS TotalAnimals,
    SUM(CASE WHEN Status = 'Healthy' THEN 1 ELSE 0 END) AS Healthy,
    SUM(CASE WHEN Status = 'Under medical care' THEN 1 ELSE 0 END) AS UnderMedicalCare,
    SUM(CASE WHEN Status = 'New Born' THEN 1 ELSE 0 END) AS NewBorn,
    SUM(CASE WHEN Status = 'maternal leave' THEN 1 ELSE 0 END) AS MaternalLeave,
    SUM(CASE WHEN Status = 'Retired' THEN 1 ELSE 0 END) AS Retired
FROM
    Animal
GROUP BY
    SID
) AS AnimalCount ON S.SID = AnimalCount.SID

LEFT JOIN (
SELECT
    CF.SID,
    SUM(HR.Rate * 40 * 4) AS TotalVetCost
FROM
    Cares_For CF
JOIN
    Employee E ON CF.ESSN = E.SSN
JOIN
    Hourly_Rate HR ON E.HRID = HR.HRID
WHERE
    E.VetFl = 1
GROUP BY
    CF.SID
) AS VetCount ON S.SID = VetCount.SID

LEFT JOIN (
SELECT
    CF.SID,
    SUM(HR.Rate * 40 * 4 ) AS TotalActsCost
FROM
    Cares_For CF
JOIN
    Employee E ON CF.ESSN = E.SSN
JOIN
    Hourly_Rate HR ON E.HRID = HR.HRID
WHERE
    E.ACTSFl = 1
GROUP BY
    CF.SID
) AS ActsCount ON S.SID = ActsCount.SID;";


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

      <a href="../mgmt_rep.html">Management and Reporting </a>

    </nav>


    <?php if (!empty($animalData)): ?>
        <table>
            <tr>
                <th>Species</th>
                <th>Total Animal </th>
                <th>Healthy </th>
                <th>Under Medical Care </th>
                <th>New Born </th>
                <th>Maternal leave </th>
                <th>Retired </th>
                <th>Food Cost</th>
                <th>Vet Cost</th>
                <th>Specialist Cost</th>
                <th>Total Cost</th>

            </tr>
            <?php foreach ($animalData as $entry): ?>
                <tr>
                    <td><?= $entry['SpeciesName']; ?></td>
                    <td><?= $entry['TotalAnimals']; ?></td>
                    <td><?= $entry['Healthy']; ?></td>
                    <td><?= $entry['UnderMedicalCare']; ?></td>
                    <td><?= $entry['NewBorn']; ?></td>
                    <td><?= $entry['Retired']; ?></td>
                    <td><?= $entry['MaternalLeave']; ?></td>
                    <td><?= $entry['TotalFoodCost']; ?></td>
                    <td><?= $entry['TotalVetCost']; ?></td>
                    <td><?= $entry['TotalActsCost']; ?></td>
                    <td><?= $entry['TotalCost']; ?></td>



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
