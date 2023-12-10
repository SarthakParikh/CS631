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

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Zoo Management System - Hourly Wages</title>";
echo "<style>";
echo "body {";
echo "    font-family: Arial, sans-serif;";
echo "    margin: 0;";
echo "    padding: 0;";
echo "    background-color: #f2f2f2;";
echo "}";
echo "";
echo "nav {";
echo "    background-color: #333;";
echo "    color: white;";
echo "    padding: 10px;";
echo "    text-align: center;";
echo "}";
echo "";
echo "nav a {";
echo "    color: white;";
echo "    text-decoration: none;";
echo "    padding: 14px 16px;";
echo "    display: inline-block;";
echo "}";
echo "";
echo "table {";
echo "    width: 80%;";
echo "    margin: 20px auto;";
echo "    border-collapse: collapse;";
echo "    background-color: #fff;";
echo "    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);";
echo "}";
echo "";
echo "th, td {";
echo "    padding: 12px;";
echo "    text-align: left;";
echo "    border-bottom: 1px solid #ddd;";
echo "}";
echo "";
echo "th {";
echo "    background-color: #333;";
echo "    color: white;";
echo "}";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<nav>";
echo "    <a href='../index.html'>Home</a>";
echo "    <a href='../daily_activity.html'>Daily Zoo Activity</a>";
echo "    <a href='add_zooaddmi.php'>Add Zoo Admission</a>";

// add_zooaddmi.php
echo "</nav>";

echo "<table>";
echo "    <thead>";
echo "        <tr>";
echo "            <th>Attendance ID</th>";
echo "            <th>Attendance Type</th>";
echo "            <th>Ticket Sold </th>";
echo "            <th>Total Revenue </th>";
echo "        </tr>";
echo "    </thead>";
echo "    <tbody>";

////////////
// SELECT
//     re.RID AS Attendance_ID,
//     CASE
//         WHEN re.revenue = za.adult_price THEN 'Adult'
//      	WHEN re.revenue = za.senior_price THEN 'Senior'
//         WHEN re.revenue = za.child_price THEN 'Child'
//         ELSE 'Unknown'
//     END AS Attendance_Type,
//     re.ticketsold AS Ticket_Sold,
//     re.revenue AS Total_Revenue
// FROM
//     zoo_admission za
// JOIN
//     revenue_event re ON za.RID = re.RID;
//////


// $sql = "SELECT
// re.RID AS Attendance_ID,
// CASE
//     WHEN re.revenue = za.adult_price THEN 'Adult'
//      WHEN re.revenue = za.senior_price THEN 'Senior'
//     WHEN re.revenue = za.child_price THEN 'Child'
//     ELSE 'Unknown'
// END AS Attendance_Type,
// re.ticketsold AS Ticket_Sold,
// re.revenue AS Total_Revenue
// FROM
// zoo_admission za
// JOIN
// revenue_event re ON za.RID = re.RID;";


$sql = "SELECT
re.RID AS Attendance_ID,
CASE
    WHEN re.Revenue = za.adult_price THEN 'Adult'
    WHEN re.Revenue = za.senior_price THEN 'Senior'
    WHEN re.Revenue = za.child_price THEN 'Child'
    ELSE 'Unknown'
END AS Attendance_Type,
sum(re.ticketsold) AS Ticket_Sold,
sum(re.revenue) AS Total_Revenue
FROM
zoo_admission za
JOIN
revenue_event re ON za.RID = re.RID;";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["Attendance_ID"] . "</td>";
        echo "<td>" . htmlspecialchars($row["Attendance_Type"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Ticket_Sold"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Total_Revenue"]) . "</td>";


       
        echo "</tr>";

    }
} else {
    echo "<tr><td colspan='4'>No records found</td></tr>";
}


echo "    </tbody>";
echo "</table>";
echo "</body>";
echo "</html>";
?>
