<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "turtleback"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $ssn = $_POST["ssn"];
    $firstName = $_POST["first_name"];
    $middleInitial = $_POST["middle_initial"];
    $lastName = $_POST["last_name"];
    $street = $_POST["street"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $zip = $_POST["zip"];
    $startDate = $_POST["start_date"];
    $managerSsn = $_POST["manager_ssn"];
    $maintenanceFlag = $_POST["maintenance_flag"];
    $actsFlag = $_POST["acts_flag"];
    $customerServiceFlag = $_POST["customer_service_flag"];
    $ticketSellerFlag = $_POST["ticket_seller_flag"];
    $vetFlag = $_POST["vet_flag"];
    $rid = $_POST["rid"];
    $hrid = $_POST["hrid"];
    if (!preg_match("/^\d{3}-\d{2}-\d{4}$/", $ssn)) {
        echo "Invalid SSN format. Please enter the SSN in the format XXX-XX-XXXX.";
        exit();
    }

    // Check if the SSN already exists in the database
    $checkQuery = "SELECT SSN FROM employees WHERE SSN = '$ssn'";
    $result = $conn->query($checkQuery);
    if ($result->num_rows > 0) {
        echo "Employee with SSN $ssn already exists. Duplicate entries are not allowed.";
        exit();
    }
    // SQL query to insert data into the database
    $sql = "INSERT INTO employee (SSN, first_name, minit, last_name, street, city, state, zip, start_date, mgr_ssn, MaintenanceFl, ActsFl, custserFl, tktsellerFl, VetFl, RID, HRID)
            VALUES ('$ssn', '$firstName', '$middleInitial', '$lastName', '$street', '$city', '$state', '$zip', '$startDate', '$managerSsn', '$maintenanceFlag', '$actsFlag', '$customerServiceFlag', '$ticketSellerFlag', '$vetFlag', '$rid', '$hrid')";

    if ($conn->query($sql) === TRUE) {
        echo "Employee added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $managerSsnQuery = "SELECT DISTINCT ManagerSSN FROM employee";
    $managerSsnResult = $conn->query($managerSsnQuery);
    // Close the database connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
</head>
<body>
    <h2>Add Employee</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        SSN: <input type="text" name="ssn" required><br>
        First Name: <input type="text" name="first_name" required><br>
        Middle Initial: <input type="text" name="middle_initial"><br>
        Last Name: <input type="text" name="last_name" required><br>
        Street: <input type="text" name="street" required><br>
        City: <input type="text" name="city" required><br>
        State: <input type="text" name="state" required><br>
        Zip: <input type="text" name="zip" required><br>
        Start Date: <input type="date" name="start_date" required><br>
        <!-- Manager SSN: <input type="text" name="manager_ssn"><br> -->

        Manager SSN: 
        <select name="manager_ssn">
            <?php
            // // Populate the dropdown with ManagerSSN values from the database
            // while ($row = $managerSsnResult->fetch_assoc()) {
            //     $selected = ($managerSsn == $row["ManagerSSN"]) ? "selected" : "";
            //     echo "<option value='{$row["ManagerSSN"]}' $selected>{$row["ManagerSSN"]}</option>";
            // }
            ?>
        </select><br>


        Maintenance Flag: <input type="text" name="maintenance_flag"><br>
        Acts Flag: <input type="text" name="acts_flag"><br>
        Customer Service Flag: <input type="text" name="customer_service_flag"><br>
        Ticket Seller Flag: <input type="text" name="ticket_seller_flag"><br>
        Vet Flag: <input type="text" name="vet_flag"><br>
        RID: <input type="text" name="rid" required><br>
        HRID: <input type="text" name="hrid" required><br>

        <input type="submit" value="Add Employee">
    </form>
</body>
</html>


<?php

$conn->close();


?>
