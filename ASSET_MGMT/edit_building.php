<?php
// // Database connection parameters
// $servername = "localhost";
// $username = "root"; // Replace with your MySQL username
// $password = ""; // Replace with your MySQL password
// $dbname = "turtleback"; // Replace with your MySQL database name

// // Create a connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check the connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// // Function to sanitize user inputs
// function sanitize($conn, $data) {
//     return mysqli_real_escape_string($conn, trim($data));
// }







// $id = $status = $birthyear = $vet_name = '';

// // Check if the form is submitted for updating data

// $bid  = $NAME = $TYPE = '';






// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
//     $id = sanitize($conn, $_POST["id"]);
//     $NAME = sanitize($conn, $_POST["NAME"]);
//     $TYPE = sanitize($conn, $_POST["TYPE"]);






//     // Update data in the database
//     $sql = "UPDATE BUILDING SET name = '$NAME', type= '$TYPE'WHERE BID = '$id';";
//     if ($conn->query($sql) === true) {
//         echo "Record updated successfully";
//         // header("Location: building.php");

//     } else {
//         echo "Error updating record: " . $conn->error;
//     }
// }

// // Fetch data based on the provided ID
// if (isset($_GET["id"])) {
//     $id = sanitize($conn, $_GET["id"]);

//     $sql = "SELECT * FROM building WHERE BID='$id'";
//     $result = $conn->query($sql);



// //   
// //  $building_id = $monthly_food_cost 
// //  = $acts_name = $vet_id = $acts_id = '';
// // // 

//     if ($result->num_rows > 0) {
//         $row = $result->fetch_assoc();
//         $bid = $row["BID"];
//         $NAME = $row["name"];
//         $TYPE = $row["type"];

        
        
//     } else {
//         echo "No record found with the given ID";
//     }
// }

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

$id = $NAME = $TYPE = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id = sanitize($conn, $_POST["building_id"]);
    $NAME = sanitize($conn, $_POST["building_name"]);
    $TYPE = sanitize($conn, $_POST["building_type"]);

    // Update data in the database
    $sql = "UPDATE building SET name = '$NAME', type = '$TYPE' WHERE BID = '$id'";
    
    if ($conn->query($sql) === true) {
        echo "Record updated successfully";
        header("Location: buildind.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

if (isset($_GET["id"])) {
    $id = sanitize($conn, $_GET["id"]);

    $sql = "SELECT * FROM building WHERE BID='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row["BID"];
        $NAME = $row["name"];
        $TYPE = $row["type"];
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
    <title>Zoo Management System - Buildings</title>
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
    <a href="#">Buildings</a>
    <a href="#">About Us</a>
    <a href="#">Contact</a>
</nav>

<form method="post">
    <label for="building_id">Building ID:</label>
    <input type="text" name="building_id" id="building_id" value="<?= htmlspecialchars($id) ?>" required><br>

    <label for="building_name">Building Name:</label>
    <input type="text" name="building_name" id="building_name" value="<?= htmlspecialchars($NAME) ?>" required><br>

    <label for="building_type">Building Type:</label>
    <input type="text" name="building_type" id="building_type" value="<?= htmlspecialchars($TYPE) ?>" required><br>

    

    
    <input type="submit" name="update" value="Update">
</form>

</body>
</html>






<?php
// Close the database connection
$conn->close();
?>