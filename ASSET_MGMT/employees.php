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




// Set initial values for form fields
$ssn = $first_name = $middle_initial = $last_name = $street = $city = $state = $zip = $start_date = $manager_ssn = $rid = $hrid = '';
$maintenance_flag = $acts_flag = $customer_service_flag = $ticket_seller_flag = $vet_flag = '';

$edit_mode = false;
$edit_row_index = -1;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $ssn = $_POST["ssn"] ?? '';
    $first_name = $_POST["first_name"] ?? '';
    $middle_initial = $_POST["middle_initial"] ?? '';
    $last_name = $_POST["last_name"] ?? '';
    $street = $_POST["street"] ?? '';
    $city = $_POST["city"] ?? '';
    $state = $_POST["state"] ?? '';
    $zip = $_POST["zip"] ?? '';
    $start_date = $_POST["start_date"] ?? '';
    $manager_ssn = $_POST["manager_ssn"] ?? '';
    $rid = $_POST["rid"] ?? '';
    $hrid = $_POST["hrid"] ?? '';

    // Set boolean values based on radio button selection
    $maintenance_flag = isset($_POST["maintenance_flag"]);
    $acts_flag = isset($_POST["acts_flag"]);
    $customer_service_flag = isset($_POST["customer_service_flag"]);
    $ticket_seller_flag = isset($_POST["ticket_seller_flag"]);
    $vet_flag = isset($_POST["vet_flag"]);

    // Check if delete operation is requested
    if (isset($_POST["delete_row"])) {
        $row_index = $_POST["delete_row"];
        $file = file("employees_data.csv");

        // Check if the row index is valid
        if (isset($file[$row_index])) {
            unset($file[$row_index]);
            file_put_contents("employees_data.csv", implode('', $file));
        }
    } elseif (isset($_POST["edit_row"])) {
        // Check if edit operation is requested
        $edit_row_index = $_POST["edit_row"];
        $file = file("employees_data.csv");

        // Check if the row index is valid
        if (isset($file[$edit_row_index])) {
            $edit_data = str_getcsv($file[$edit_row_index]);
            $ssn = $edit_data[0];
            $first_name = $edit_data[1];
            $middle_initial = $edit_data[2];
            $last_name = $edit_data[3];
            $street = $edit_data[4];
            $city = $edit_data[5];
            $state = $edit_data[6];
            $zip = $edit_data[7];
            $start_date = $edit_data[8];
            $manager_ssn = $edit_data[9];
            $maintenance_flag = ($edit_data[10] == 'true');
            $acts_flag = ($edit_data[11] == 'true');
            $customer_service_flag = ($edit_data[12] == 'true');
            $ticket_seller_flag = ($edit_data[13] == 'true');
            $vet_flag = ($edit_data[14] == 'true');
            $rid = $edit_data[15];
            $hrid = $edit_data[16];
            $edit_mode = true;
        }
    } else {
        // Store data in a CSV file (you can modify this to store in a database)
        $file = fopen("employees_data.csv", "a");
        fputcsv($file, [
            $ssn,
            $first_name,
            $middle_initial,
            $last_name,
            $street,
            $city,
            $state,
            $zip,
            $start_date,
            $manager_ssn,
            $maintenance_flag,
            $acts_flag,
            $customer_service_flag,
            $ticket_seller_flag,
            $vet_flag,
            $rid,
            $hrid
        ]);
        fclose($file);

        // Reset form fields after submission
        $ssn = $first_name = $middle_initial = $last_name = $street = $city = $state = $zip = $start_date = $manager_ssn = $rid = $hrid = '';
        $maintenance_flag = $acts_flag = $customer_service_flag = $ticket_seller_flag = $vet_flag = '';

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
echo "<title>Zoo Management System - Employees</title>";
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
echo "input, label {";
echo "    margin-bottom: 15px;";
echo "}";
echo "";
echo "input[type='text'], input[type='date'] {";
echo "    width: 100%;";
echo "    padding: 10px;";
echo "    border: 1px solid #ccc;";
echo "    border-radius: 4px;";
echo "    box-sizing: border-box;";
echo "}";
echo "";
echo "input[type='radio'] {";
echo "    margin-right: 5px;";
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

echo "<nav>";
echo "    <a href='../index.html'>Home</a>";
echo "    <a href='../asset_mgmt.html'>Asset Management</a>";
echo " <a href='add_employees.php' class='edit-btn'>Add Employeess</a>";

echo "</nav>";
// echo "<form method='post'>";
// echo "    <label for='ssn'>SSN:</label>";
// echo "    <input type='text' name='ssn' id='ssn' value='" . htmlspecialchars($ssn) . "' required>";
// echo "";
// echo "    <label for='first_name'>First Name:</label>";
// echo "    <input type='text' name='first_name' id='first_name' value='" . htmlspecialchars($first_name) . "' required>";
// echo "";
// echo "    <label for='middle_initial'>Middle Initial:</label>";
// echo "    <input type='text' name='middle_initial' id='middle_initial' value='" . htmlspecialchars($middle_initial) . "'>";
// echo "";
// echo "    <label for='last_name'>Last Name:</label>";
// echo "    <input type='text' name='last_name' id='last_name' value='" . htmlspecialchars($last_name) . "' required>";
// echo "";
// echo "    <label for='street'>Street:</label>";
// echo "    <input type='text' name='street' id='street' value='" . htmlspecialchars($street) . "' required>";
// echo "";
// echo "    <label for='city'>City:</label>";
// echo "    <input type='text' name='city' id='city' value='" . htmlspecialchars($city) . "' required>";
// echo "";
// echo "    <label for='state'>State:</label>";
// echo "    <input type='text' name='state' id='state' value='" . htmlspecialchars($state) . "' required>";
// echo "";
// echo "    <label for='zip'>Zip:</label>";
// echo "    <input type='text' name='zip' id='zip' value='" . htmlspecialchars($zip) . "' required>";
// echo "";
// echo "    <label for='start_date'>Start Date:</label>";
// echo "    <input type='date' name='start_date' id='start_date' value='" . htmlspecialchars($start_date) . "' required>";
// echo "";
// echo "    <label for='manager_ssn'>Manager SSN:</label>";
// echo "    <input type='text' name='manager_ssn' id='manager_ssn' value='" . htmlspecialchars($manager_ssn) . "' required>";
// echo "";
// echo "    <label for='maintenance_flag'>Maintenance Flag:</label>";
// echo "    <label><input type='radio' name='maintenance_flag' value='true' " . ($maintenance_flag ? "checked" : "") . "> True</label>";
// echo "    <label><input type='radio' name='maintenance_flag' value='false' " . (!$maintenance_flag ? "checked" : "") . "> False</label>";
// echo "";
// echo "    <label for='acts_flag'>Acts Flag:</label>";
// echo "    <label><input type='radio' name='acts_flag' value='true' " . ($acts_flag ? "checked" : "") . "> True</label>";
// echo "    <label><input type='radio' name='acts_flag' value='false' " . (!$acts_flag ? "checked" : "") . "> False</label>";
// echo "";
// echo "    <label for='customer_service_flag'>Customer Service Flag:</label>";
// echo "    <label><input type='radio' name='customer_service_flag' value='true' " . ($customer_service_flag ? "checked" : "") . "> True</label>";
// echo "    <label><input type='radio' name='customer_service_flag' value='false' " . (!$customer_service_flag ? "checked" : "") . "> False</label>";
// echo "";
// echo "    <label for='ticket_seller_flag'>Ticket Seller Flag:</label>";
// echo "    <label><input type='radio' name='ticket_seller_flag' value='true' " . ($ticket_seller_flag ? "checked" : "") . "> True</label>";
// echo "    <label><input type='radio' name='ticket_seller_flag' value='false' " . (!$ticket_seller_flag ? "checked" : "") . "> False</label>";
// echo "";
// echo "    <label for='vet_flag'>Vet Flag:</label>";
// echo "    <label><input type='radio' name='vet_flag' value='true' " . ($vet_flag ? "checked" : "") . "> True</label>";
// echo "    <label><input type='radio' name='vet_flag' value='false' " . (!$vet_flag ? "checked" : "") . "> False</label>";
// echo "";
// echo "    <label for='rid'>RID:</label>";
// echo "    <input type='text' name='rid' id='rid' value='" . htmlspecialchars($rid) . "' required>";
// echo "";
// echo "    <label for='hrid'>HRID:</label>";
// echo "    <input type='text' name='hrid' id='hrid' value='" . htmlspecialchars($hrid) . "' required>";
// echo "";
// echo "    <input type='submit' value='" . ($edit_mode ? 'Update' : 'Submit') . "'>";

// // Display Cancel button in edit mode
// if ($edit_mode) {
//     echo "<a href='{$_SERVER['PHP_SELF']}' style='margin-left: 10px; text-decoration: none;'>";
//     echo "<input type='button' value='Cancel' class='delete-btn'>";
//     echo "</a>";
// }

// echo "</form>";
echo "";
echo "<table>";
echo "    <thead>";
echo "        <tr>";
echo "            <th>SSN</th>";
echo "            <th>First Name</th>";
echo "            <th>Middle Initial</th>";
echo "            <th>Last Name</th>";
echo "            <th>Street</th>";
echo "            <th>City</th>";
echo "            <th>State</th>";
echo "            <th>Zip</th>";
echo "            <th>Start Date</th>";
echo "            <th>Manager SSN</th>";
echo "            <th>Job Type</th>";         
echo "            <th>RID</th>";
echo "            <th>HRID</th>";
echo "            <th>Action</th>";
echo "        </tr>";
echo "    </thead>";
echo "    <tbody>";

$sql = "SELECT
e.SSN,
e.first_name,
e.minit,
e.last_name,
e.Street,
e.City,
e.State,
e.Zip,
e.Start_date,
e.Mgr_ssn,
CASE
    WHEN e.MaintenanceFl = '1' THEN 'Maintenance'
    WHEN e.ACTSFl = '1' THEN 'ACTS'
    WHEN e.VetFl = '1' THEN 'Vet'
    WHEN e.CustSerFl = '1' THEN 'Customer Service'
    WHEN e.tktsellerfl = '1' THEN 'Ticket Seller'
    ELSE 'Unknown'
END AS JobType,
e.RID,
e.HRID
FROM
Employee e;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["SSN"] . "</td>";
        echo "<td>" . htmlspecialchars($row["first_name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["minit"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["last_name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Street"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["City"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["State"]) . "</td>";

        echo "<td>" . htmlspecialchars($row["Zip"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Start_date"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Mgr_ssn"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["JobType"]) . "</td>";



        echo "<td>" . htmlspecialchars($row["RID"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["HRID"]) . "</td>";


        echo "<td><a href='edit_employees.php?id=" . $row["SSN"] . "' class='edit-btn'>Edit</a></td>";
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

