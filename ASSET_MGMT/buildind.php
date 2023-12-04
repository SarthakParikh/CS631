<?php





$servername = "localhost";
$username = "root";
$password = "";
$dbname = "turtleback"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// SELECT * FROM `building`




// Set initial values for form fields
$building_id = $building_name = $building_type = '';
$edit_mode = false;
$edit_row_index = -1;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $building_id = $_POST["building_id"] ?? '';
    $building_name = $_POST["building_name"] ?? '';
    $building_type = $_POST["building_type"] ?? '';

    // Check if delete operation is requested
    if (isset($_POST["delete_row"])) {
        $row_index = $_POST["delete_row"];
        $file = file("building_data.csv");

        // Check if the row index is valid
        if (isset($file[$row_index])) {
            unset($file[$row_index]);
            file_put_contents("building_data.csv", implode('', $file));
        }
    } elseif (isset($_POST["edit_row"])) {
        // Check if edit operation is requested
        $edit_row_index = $_POST["edit_row"];
        $file = file("building_data.csv");

        // Check if the row index is valid
        if (isset($file[$edit_row_index])) {
            $edit_data = str_getcsv($file[$edit_row_index]);
            $building_id = $edit_data[0];
            $building_name = $edit_data[1];
            $building_type = $edit_data[2];
            $edit_mode = true;
        }
    } else {
        // Store data in a CSV file (you can modify this to store in a database)
        $file = fopen("building_data.csv", "a");
        fputcsv($file, [$building_id, $building_name, $building_type]);
        fclose($file);

        // Reset form fields after submission
        $building_id = $building_name = $building_type = '';

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
echo "<title>Zoo Management System - Buildings</title>";
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
echo "    <a href='#'>Buildings</a>";
echo "    <a href='#'>About Us</a>";
echo "    <a href='#'>Contact</a>";
echo "</nav>";
echo "";

echo "";
echo "<table>";
echo "    <thead>";
echo "        <tr>";
echo "            <th>Building ID</th>";
echo "            <th>Building Name</th>";
echo "            <th>Building Type</th>";
echo "            <th>Action</th>";
echo "        </tr>";
echo "    </thead>";
echo "    <tbody>";

// Read and display data from the CSV file
// $file = fopen("building_data.csv", "r");
// $index = 0;
// while (($data = fgetcsv($file)) !== false) {
//     echo "<tr>";
//     foreach ($data as $value) {
//         echo "<td>" . htmlspecialchars($value) . "</td>";
//     }


$sql = "SELECT * FROM building";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["BID"] . "</td>";
       
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["type"]) . "</td>";

        echo "<td><a href='edit_building.php?id=" . $row["BID"] . "' class='edit-btn'>Edit</a></td>";
        echo "</tr>";

    }
} else {
    echo "<tr><td colspan='4'>No records found</td></tr>";
}

    // echo "<td>";
    // echo "<form method='post' style='display: inline;'>";
    // echo "<input type='hidden' name='delete_row' value='{$index}'>";
    // echo "<input type='submit' class='delete-btn' value='Delete'>";
    // echo "</form>";
    // echo "<form method='post' style='display: inline;'>";
    // echo "<input type='hidden' name='edit_row' value='{$index}'>";
    // echo "<input type='submit' class='edit-btn' value='Edit'>";
    // echo "</form>";
    // echo "</td>";
    // echo "</tr>";




echo "    </tbody>";
echo "</table>";
echo "</body>";
echo "</html>";
?>
