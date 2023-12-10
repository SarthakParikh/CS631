<?php
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

// Function to sanitize user inputs
function sanitize($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}

// Initialize variables
$id = $status = $birthyear = $vet_name = '';

// Check if the form is submitted for updating data

// $animal_id = $birth_year = $current_status = $species_id = $cage_id = $building_id = $monthly_food_cost = $acts_name = $vet_id = $acts_id = '';
$HRID = $RATE = '';





if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $HRID = sanitize($conn, $_POST["id"]);
    $RATE = sanitize($conn, $_POST["RATE"]);





    

    // Update data in the database
    $sql = "UPDATE hourly_rate SET rate = '$RATE' WHERE HRID = '$HRID';";
    echo $sql;
    if ($conn->query($sql) === true) {
        echo "Record updated successfully";
        header("Location: hourly_wages.php");

    } else {
        echo "Error updating record: " . $conn->error;
    }
}

if (isset($_GET["id"])) {
    $id = sanitize($conn, $_GET["id"]);

    $sql = "SELECT * FROM hourly_rate WHERE HRID='$id'";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $HRDI = $row["HRID"];
        $RATE = $row["Rate"];
 
        
        
    } else {
        echo "No record found with the given ID";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Animal - Zoo Management System</title>
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

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
<nav>
    <a href='../index.html'>Home</a>
   <a href='../asset_mgmt.html'>Asset Management</a>
 
</nav>
<form method="post">
    <label for="animal_id">ID :</label>
    <input type="text" name="id" id="id" value="<?= htmlspecialchars($id) ?>" readonly disable>

    <label for="birth_year">Rate :</label>
    <input type="text" name="RATE" id="RATE" value="<?= htmlspecialchars($RATE) ?>" required><br>


    <input type="submit" name="update" value="Update">
   
</form>

</body>
</html>

<?php
$conn->close();
?>
