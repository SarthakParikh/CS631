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
echo "    <a href='add_con.php'>Add Concession Item</a>";

echo "</nav>";

echo "<table>";
echo "    <thead>";
echo "        <tr>";
echo "            <th>Concessions ID</th>";
echo "            <th>Concessions Name</th>";        
echo "            <th>revenue</th>";
echo "        </tr>";
echo "    </thead>";
echo "    <tbody>";



$sql = "SELECT
r.RID,
r.name,
SUM( re.Revenue ) AS total_revenue,
p.Product
FROM
revenue_type r
JOIN
revenue_event re ON r.RID = re.RID
JOIN
concession p ON re.RID = p.RID

WHERE r.type ='concession'
GROUP BY
r.name
ORDER BY r.RID;";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["RID"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["total_revenue"]) . "</td>";


       
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
