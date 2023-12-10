<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
 
        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type='submit'] {
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        input[type='submit']:hover {
            background-color: #555;
        }

        .radio-group {
            margin-bottom: 15px;
        }

        .radio-group label {
            display: block;
            margin-bottom: 8px;
        }

        .radio-group input {
            margin-right: 5px;
        }
    </style>
</head>
<body>

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
function generateManagerOptions($conn) {
    $managerQuery = "SELECT DISTINCT mgr_ssn FROM employee;";
    $managerResult = $conn->query($managerQuery);

    if ($managerResult->num_rows > 0) {
        while ($row = $managerResult->fetch_assoc()) {
            echo "<option value='" . $row['mgr_ssn'] . "'>" . $row['mgr_ssn'] . "</option>";
        }
    } else {
        echo "<option value=''>No Managers available</option>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $selectedFlag = $_POST['job_type'];

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

    $ssn = $_POST['ssn']; // SSN of the employee to edit
    // if (!preg_match("/^\d{9}$/", $ssn)) {
    //     die("Invalid SSN. SSN should be 9 digits.");
    // }
    $firstName = $_POST['first_name'];
    $middleInitial = $_POST['middle_initial'];
    $lastName = $_POST['last_name'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $startDate = $_POST['start_date'];
    $managerSsn = $_POST['manager_ssn'];
    $rid = $_POST['rid'];
    $hrid = $_POST['hrid'];
    // $maintenanceFlag = $flags['maintenance'];
    // $actsFlag = $flags['acts'];
    // $customerServiceFlag = $flags['customerService'];
    // $ticketSellerFlag = $flags['ticketSeller'];
    // $vetFlag = $flags['vet'];
    

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
    $maintenanceFlag = $flags['maintenance'];
    $actsFlag = $flags['acts'];
    $customerServiceFlag = $flags['customerService'];
    $ticketSellerFlag = $flags['ticketSeller'];
    $vetFlag = $flags['vet'];
    
    
    if($ticketSellerFlag){
        $rid = 7;
    
    }
    else{
        $rid = 0;
    }
    
    


    $sql = "UPDATE employee SET
            first_name = '$firstName',
            minit = '$middleInitial',
            last_name = '$lastName',
            street = '$street',
            city = '$city',
            State = '$state',
            zip = '$zip',
            start_date = '$startDate',
            mgr_ssn = '$managerSsn',
            MaintenanceFl = '$maintenanceFlag',
            ActsFl   = '$actsFlag',
            custserFl = '$customerServiceFlag',
            tktsellerFl = '$ticketSellerFlag',
            VetFl = '$vetFlag',
            RID = '$rid',
            HRID = '$hrid'
            WHERE SSN = '$ssn'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
        header("Location: employees.php");

    } else {
        echo "Error updating record: " . $conn->error;
    }
}



if (isset($_GET["id"])) {
    $id = sanitize($conn, $_GET["id"]);

    $sql = "SELECT * FROM employee WHERE SSN='$id'";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ssn = $row["SSN"];
        $first_name = $row["first_name"];
        $middle_initial = $row["minit"];
        $last_name = $row["last_name"];
        $street = $row["street"];
        $city = $row["city"];
        $state = $row["State"];
        $zip = $row["zip"];
        $start_date = $row["start_date"] ;  

         $manager_ssn = $row["mgr_ssn"];
         $state = $row["State"];
         $RID = $row["RID"];
          $HRID = $row["HRID"];

    // $maintenanceFlag = $row["MaintenanceFl"];
    // $actsFlag = $row["ActsFl"];
    // $customerServiceFlag = $row["custserFl"];
    // $ticketSellerFlag = $row["tktsellerFl"];
    // $vetFlag = $row["VetFl"];
     } else {
        echo "No record found with the given ID";
    }
}


?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="ssn">SSN:</label>
    <input type="text" name="ssn" id="ssn" value='<?php echo $ssn; ?>' readonly><br>

    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" readonly><br>
    <label for="middle_initial">Middle Initial:</label>
    <input type="text" name="middle_initial" id="middle_initial" value="<?php echo $middle_initial; ?>" readonly><br>

    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" readonly><br>

    <label for="street">Street:</label>
    <input type="text" name="street" id="street" value="<?php echo $street; ?>" required><br>

    <label for="city">City:</label>
    <input type="text" name="city" id="city" value="<?php echo $city; ?>" required><br>

    <label for="state">State:</label>
    <input type="text" name="state" id="state" value="<?php echo $state; ?>" required><br>

    <label for="zip">Zip:</label>
    <input type="number" min={5} max={5} name="zip" id="zip" value="<?php echo $zip; ?>" required><br>

    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" id="start_date" value="<?php echo $start_date; ?>" required><br>


    <div class="manager-dropdown">
        <label for="manager_ssn">Manager SSN:</label>
        <pre> 
Use the following Manager SSN based on your departments
maintenance: 152-19-0597 
animal care: 112-84-3154
Customer service: 183-22-8835
ticket seller: 127-37-7794
vet: 309-56-9988 
</pre>
        <select name="manager_ssn" id="manager_ssn" value="<?php echo $manager_ssn; ?>" required>
            <?php generateManagerOptions($conn); ?>
        </select>
    </div>

   

    <label>Job Type:</label>
    <br>
    <div class="radio-group">
       
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
    </div>

    <label for="rid">RID:</label>
    <input type="text" name="rid" id="rid" value="<?php echo $RID; ?>" required><br>

    <label for="hrid">HRID:</label>
    <select name="hrid" id="hrid" value="<?php echo $hrid; ?>" required>
        <?php
     
        $hridQuery = "SELECT HRID FROM hourly_rate";
        $hridResult = $conn->query($hridQuery);

        if ($hridResult->num_rows > 0) {
            while ($row = $hridResult->fetch_assoc()) {
                echo "<option value='" . $row['HRID'] . "'>" . $row['HRID'] . "</option>";
            }
        } else {
            echo "<option value=''>No HRIDs available</option>";
        }
        ?>
    </select><br>

    <input type="submit" value="Update Employee">
</form>

</body>
</html>

<?php

$conn->close();
?>
