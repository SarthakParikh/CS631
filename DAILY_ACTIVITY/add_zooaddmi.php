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

function sanitize($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $attractionID = 7;
    
    $revenue = $_POST["revenue"];
    echo $revenue;
    $current_date = date("Y-m-d");
    $current_time = date("H:i:s");
    // Insert the new attraction into the database
    $query_rev = "SELECT  $revenue FROM zoo_admission";
    $result_rev = $conn->query($query_rev);
    $resultString = "";
    while ($row = $result_rev->fetch_assoc()) {
        $resultString = $row[$revenue];
    }
  echo $resultString;
   $tckSold = 1;
   
   
    $insertQuery = "INSERT INTO revenue_event (RID, Date, Time ,Revenue,ticketsold) VALUES ('$attractionID', '$current_date','$current_time', '$resultString','$tckSold')";
    if ($conn->query($insertQuery) === TRUE) {
        echo "Attraction added successfully!";
        header("Location: attendance.php");

    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Attraction</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        select,
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 4px;
        }

        select {
            cursor: pointer;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        } 
    </style>
</head>
<body>
    <h2>Add Zoo Admission</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Type:
        <select name="revenue">
            <option value="adult_price">Adult</option>
            <option value="child_price">Child</option>
            <option value="senior_price">Senior</option>
        </select><br>
        <input type="submit" value="Sell ticket">
    </form>
</body>
</html>

<?php

$conn->close();
?>