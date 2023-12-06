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



$attraction_name = $attraction_id = $building_name = $building_id = $species_name = $species_id = '';
$edit_mode = false;
$edit_row_index = -1;
 
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $attraction_name = $_POST["attraction_name"] ?? '';
    $attraction_id = $_POST["attraction_id"] ?? '';
    $building_name = $_POST["building_name"] ?? '';
    $building_id = $_POST["building_id"] ?? '';
    $species_name = $_POST["species_name"] ?? '';
    $species_id = $_POST["species_id"] ?? '';

    // Check if delete operation is requested
    if (isset($_POST["delete_row"])) {
        $row_index = $_POST["delete_row"];
        $file = file("attractions_data.csv");

        // Check if the row index is valid
        if (isset($file[$row_index])) {
            unset($file[$row_index]);
            file_put_contents("attractions_data.csv", implode('', $file));
        }
    } elseif (isset($_POST["edit_row"])) {
        // Check if edit operation is requested
        $edit_row_index = $_POST["edit_row"];
        $file = file("attractions_data.csv");

        // Check if the row index is valid
        if (isset($file[$edit_row_index])) {
            $edit_data = str_getcsv($file[$edit_row_index]);
            $attraction_name = $edit_data[0];
            $attraction_id = $edit_data[1];
            $building_name = $edit_data[2];
            $building_id = $edit_data[3];
            $species_name = $edit_data[4];
            $species_id = $edit_data[5];
            $edit_mode = true;
        }
    } else {
        // Store data in a CSV file (you can modify this to store in a database)
        $file = fopen("attractions_data.csv", "a");
        fputcsv($file, [$attraction_name, $attraction_id, $building_name, $building_id, $species_name, $species_id]);
        fclose($file);

        // Reset form fields after submission
        $attraction_name = $attraction_id = $building_name = $building_id = $species_name = $species_id = '';

        // Redirect to prevent form resubmission on page refresh
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Display the table
echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Zoo Management System - Attractions</title>";
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

echo "</nav>";
echo "";
// echo "<form method='post'>";
// echo "    <label for='attraction_name'>Attraction Name:</label>";
// echo "    <input type='text' name='attraction_name' id='attraction_name' value='" . htmlspecialchars($attraction_name) . "' required><br>";
// echo "";
// echo "    <label for='attraction_id'>Attraction ID:</label>";
// echo "    <input type='text' name='attraction_id' id='attraction_id' value='" . htmlspecialchars($attraction_id) . "' required><br>";
// echo "";
// echo "    <label for='building_name'>Building Name:</label>";
// echo "    <input type='text' name='building_name' id='building_name' value='" . htmlspecialchars($building_name) . "' required><br>";
// echo "";
// echo "    <label for='building_id'>Building ID:</label>";
// echo "    <input type='text' name='building_id' id='building_id' value='" . htmlspecialchars($building_id) . "' required><br>";
// echo "";
// echo "    <label for='species_name'>Species Name:</label>";
// echo "    <input type='text' name='species_name' id='species_name' value='" . htmlspecialchars($species_name) . "' required><br>";
// echo "";
// echo "    <label for='species_id'>Species ID:</label>";
// echo "    <input type='text' name='species_id' id='species_id' value='" . htmlspecialchars($species_id) . "' required><br>";
// echo "";
// echo "    <input type='submit' value='" . ($edit_mode ? 'Update' : 'Submit') . "'>";



echo "</form>";
echo "";
echo "<table>";
echo "    <thead>";
echo "        <tr>";
echo "            <th>Attraction ID</th>";
echo "            <th>Attraction Name</th>";
echo "            <th>Building ID</th>";
echo "            <th>Building Name</th>";

echo "            <th>Action</th>";
echo "        </tr>";
echo "    </thead>";
echo "    <tbody>";

$sql = "SELECT
rt.name AS Attraction_Name,
rt.RID AS Attraction_ID,
b.name AS Building_Name,
b.BID AS Building_ID
FROM
revenue_type rt
JOIN
building b ON rt.BID = b.BID;
";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["Attraction_ID"] . "</td>";
        echo "<td>" . htmlspecialchars($row["Attraction_Name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Building_ID"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Building_Name"]) . "</td>";


        echo "<td><a href='edit_attractions.php?id=" . $row["Attraction_ID"] . "' class='edit-btn'>Edit</a></td>";
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
