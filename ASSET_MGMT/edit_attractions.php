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

$attraction_name = $attraction_id = $building_name = $building_id = $species_name = $species_id = '';






if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    $id = sanitize($conn, $_POST["id"]);
    $current_status = sanitize($conn, $_POST["attraction_name"]);
    $birth_year = sanitize($conn, $_POST["birth_year"]);
    $attraction_id = sanitize($conn, $_POST["attraction_id"]);
    $cage_id = sanitize($conn, $_POST["cage_id"]);
    $building_id = sanitize($conn, $_POST["building_id"]);






    // Update data in the database
    $sql = "UPDATE revenue_type SET name = '$current_status' , BID = ' $building_id' WHERE RID = '$attraction_id'  ;";
    if ($conn->query($sql) === true) {
        echo "Record updated successfully";
        header("Location: attractions.php");

    } else {
        echo "Error updating record: " . $conn->error;
    }
}

if (isset($_GET["id"])) {
    $id = sanitize($conn, $_GET["id"]);
    $sql = "SELECT rt.name AS Attraction_Name, rt.RID AS Attraction_ID,b.name AS Building_Name, b.BID AS Building_ID FROM revenue_type rt JOIN building b ON rt.BID = b.BID WHERE RID='$id';";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {

// $attraction_name = $attraction_id = $building_name = $building_id;
// 
        $row = $result->fetch_assoc();
        $attraction_id = $row["Attraction_ID"];
        $attraction_name = $row["Attraction_Name"];
        $building_name = $row["Building_Name"];
        $building_id = $row["Building_ID"];

        
        
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
    <title>Zoo Management System - Attractions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        nav {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 14px 16px;
            display: inline-block;
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

        input[type='submit'] {
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        input[type='submit']:hover {
            background-color: #555;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: white;
        }

        .delete-btn, .edit-btn {
            background-color: #f44336;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }

        .delete-btn:hover, .edit-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<nav>
    <a href="../index.html">Home</a>
    <a href="../asset_mgmt.html">Asset Management</a>
</nav>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

    <label for="attraction_id">Attraction ID:</label>
    <input type="text" name="attraction_id" id="attraction_id" value="<?php echo htmlspecialchars($attraction_id); ?>" readonly><br>

    <label for="attraction_name">Attraction Name:</label>
    <input type="text" name="attraction_name" id="attraction_name" value="<?php echo htmlspecialchars($attraction_name); ?>" required><br>

   
    <label for="building_id">Building ID:</label>
    <input type="text" name="building_id" id="building_id" value="<?php echo htmlspecialchars($building_id); ?>" required><br>



    <input type="submit" value="Submit">
</form>

</body>
</html>


<?php
$conn->close();
?>
