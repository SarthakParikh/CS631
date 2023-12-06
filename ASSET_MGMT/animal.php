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

$animal_id = $birth_year = $current_status = $species_id = $cage_id = $building_id = $monthly_food_cost = $acts_name = $vet_id = $acts_id = '';
$edit_mode = false;
$edit_row_index = -1;


echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Zoo Management System</title>";
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
echo "form {";
echo "    max-width: 600px;";
echo "    margin: 20px auto;";
echo "    padding: 20px;";
echo "    background-color: #fff;";
echo "    border-radius: 8px;";
echo "    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);";
echo "}";
echo "";
echo "label {";
echo "    display: block;";
echo "    margin-bottom: 8px;";
echo "}";
echo "";
echo "input {";
echo "    width: 100%;";
echo "    padding: 10px;";
echo "    margin-bottom: 15px;";
echo "    border: 1px solid #ccc;";
echo "    border-radius: 4px;";
echo "    box-sizing: border-box;";
echo "}";
echo "";
echo "input[type='submit'] {";
echo "    background-color: #333;";
echo "    color: white;";
echo "    cursor: pointer;";
echo "}";
echo "";
echo "input[type='submit']:hover {";
echo "    background-color: #555;";
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
echo "";
echo ".delete-btn, .edit-btn {";
echo "    background-color: #f44336;";
echo "    color: white;";
echo "    padding: 6px 12px;";
echo "    border: none;";
echo "    border-radius: 4px;";
echo "    cursor: pointer;";
echo "    margin-right: 5px;";
echo "}";
echo "";
echo ".delete-btn:hover, .edit-btn:hover {";
echo "    background-color: #d32f2f;";
echo "}";
echo "</style>";
echo "</head>";
echo "<body>";
echo "";
echo "<nav>";
echo "    <a href='../index.html'>Home</a>";
echo "    <a href='../asset_mgmt.html'>Asset Management</a>";
echo "    <a href='#'>About Us</a>";
echo "    <a href='#'>Contact</a>";
echo "</nav>";
echo "";
echo "<a href='add_animal.php' class='edit-btn'>Add Animal Details</a>";

echo "";
echo "<table>";
echo "    <thead>";
echo "        <tr>";
echo "            <th>Animal ID</th>";
echo "            <th>Current Status</th>";
echo "            <th>Birth Year</th>";
echo "            <th>Species ID</th>";
echo "            <th>Cage ID</th>";
echo "            <th>Building ID</th>";
echo "            <th>Monthly Food Cost</th>";
echo "            <th>Action</th>";
echo "        </tr>";
echo "    </thead>";
echo "    <tbody>";


 
$sql = "SELECT A.AID, A.status, A.BirthYear, A.SID, A.ENID, A.BID, FC.food_cost FROM Animal A JOIN species FC ON A.SID = FC.SID;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["AID"] . "</td>";
        echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["BirthYear"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["SID"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["BID"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["ENID"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["food_cost"]) . "</td>";

        echo "<td><a href='edit_animal.php?id=" . $row["AID"] . "' class='edit-btn'>Edit</a></td>";
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
