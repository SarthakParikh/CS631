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



$managerSsnQuery = "SELECT DISTINCT mgr_ssn FROM employee";
$managerSsnResult = $conn->query($managerSsnQuery);

$hr_id_value = "SELECT HRID FROM hourly_rate";
$hr_id_value_result = $conn->query($hr_id_value);


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
 


    $selectedFlag = $_POST['flag'];

    // Reset all other flags to 0
    $flags = [
        'maintenance' => 0,
        'acts' => 0,
        'customerService' => 0,
        'ticketSeller' => 0,
        'vet' => 0,
    ];

    // Set the selected flag to 1
    $flags[$selectedFlag] = 1;



    $hrid = $_POST["hrid"];
    $maintenanceFlag = $flags['maintenance'];
$actsFlag = $flags['acts'];
$customerServiceFlag = $flags['customerService'];
$ticketSellerFlag = $flags['ticketSeller'];
$vetFlag = $flags['vet'];



if($ticketSellerFlag){
    $rid = 1;

}
else{
    $rid = 0;
}



    if (!preg_match("/^\d{3}-\d{2}-\d{4}$/", $ssn)) {
        echo "Invalid SSN format. Please enter the SSN in the format XXX-XX-XXXX.";
        exit();
    }

    // Check if the SSN already exists in the database
    $checkQuery = "SELECT SSN FROM employee WHERE SSN = '$ssn'";
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
        header("Location: employees.php");

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

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
<nav>
    <a href='../index.html'>Home</a>
   <a href='../asset_mgmt.html'>Asset Management</a>
 
</nav>
    <h2>Add Employee</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        SSN: <input type="text" name="ssn" placeholder="xxx-xx-xxxx"required><br>
        First Name: <input type="text" name="first_name" placeholder="first name" required><br>
        Middle Initial: <input type="text" name="middle_initial" placeholder="middle intial"><br>
        Last Name: <input type="text" name="last_name" placeholder="last name"required><br>
        Street: <input type="text" name="street" placeholder="street address"required><br>
        City: <input type="text" name="city" placeholder="city"required><br>
        State: <input type="text" name="state" placeholder="full state name"required><br>
        Zip: <input type="text" name="zip" placeholder="xxxxx"required><br>
        Start Date: <input type="date" name="start_date" required><br> </br>
   

        Manager SSN: 
        <pre> 
Use the following Manager SSN based on your departments
maintenance: 152-19-0597 
animal care: 112-84-3154
Customer service: 183-22-8835
ticket seller: 127-37-7794
vet: 309-56-9988 
</pre>
        <select name="manager_ssn" > 
    <?php
    while ($row = $managerSsnResult->fetch_assoc()) {
        echo "<option value='" . $row['mgr_ssn'] . "'>" . $row['mgr_ssn'] . "</option>";
    }
    ?>
</select>


</br>


<br>



    <label>
        <input type="radio" name="flag" value="maintenance" id="maintenanceFlag"> Maintenance Flag
    </label>
    <br>
    <label>
        <input type="radio" name="flag" value="acts" id="actsFlag"> Acts Flag
    </label>
    <br>
    <label>
        <input type="radio" name="flag" value="customerService" id="customerServiceFlag"> Customer Service Flag
    </label>
    <br>
    <label>
        <input type="radio" name="flag" value="ticketSeller" id="ticketSellerFlag"> Ticket Seller Flag
    </label>
    <br>
    <label>
        <input type="radio" name="flag" value="vet" id="vetFlag"> Vet Flag
    </label>
    <br>
    <br>



    <!-- RID: <input type="text" name="rid" required><br> -->
 <!-- HRID: <input type="text" name="hrid" required><br> -->


 HRID: 

 <pre> 
Use the following HRID based on your departments
maintenance: 1
animal care: 2
Customer service: 3
ticket seller: 4
vet: 5 
</pre>
        <select name="hrid">
    <?php
    while ($row = $hr_id_value_result->fetch_assoc()) {
        echo "<option value='" . $row['HRID'] . "'>" . $row['HRID'] . "</option>";
    }
    ?>
</select>
<br>
<br>



        <input type="submit" value="Add Employee">
    </form>
</body>
</html>


<?php

$conn->close();


?>
