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

// Function to get revenue options based on the selected Attraction ID
function getRevenueOptions($attractionID, $conn) {
    $query = "SELECT DISTINCT revenue FROM attractions WHERE attraction_id = $attractionID";
    $result = $conn->query($query);

    $options = [];
    while ($row = $result->fetch_assoc()) {
        $options[] = $row['revenue'];
    }

    return $options;
}

function getAttractionIDs($conn) {
    $query = "SELECT  RID FROM revenue_type";
    $result = $conn->query($query);

    $attractionIDs = [];
    while ($row = $result->fetch_assoc()) {
        $attractionIDs[] = $row['attraction_id'];
    }

    return $attractionIDs;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attractionID = $_POST["attraction_id"];
    $location = $_POST["attraction_location"];
    $revenue = $_POST["revenue"];

    // Insert the new attraction into the database
    $insertQuery = "INSERT INTO attractions (attraction_id, attraction_location, revenue) VALUES ('$attractionID', '$location', '$revenue')";
    if ($conn->query($insertQuery) === TRUE) {
        echo "Attraction added successfully!";
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}

// Close the database connection

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
    <h2>Add Attraction</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Attraction ID:
        <select name="attraction_id" required>
            <?php
            // Populate the attraction ID dropdown
            $attractionIDs = getAttractionIDs($conn);
            foreach ($attractionIDs as $attractionID) {
                echo "<option value=\"$attractionID\">$attractionID</option>";
            }
            ?>
        </select><br>
        Attraction Location: <input type="text" name="attraction_location" required><br>
        Revenue:
        <select name="revenue">
            <?php
            // Populate the revenue dropdown based on the selected Attraction ID
            if (isset($_POST["attraction_id"])) {
                $attractionID = $_POST["RID"];
                $revenueOptions = getRevenueOptions($attractionID, $conn);

                foreach ($revenueOptions as $option) {
                    echo "<option value=\"$option\">$option</option>";
                }
            }
            ?>
            <option value="Value1">Value1</option>
            <option value="Value2">Value2</option>
            <option value="Value3">Value3</option>
        </select><br>
        <input type="submit" value="Add Attraction">
    </form>
</body>
</html>

<?php

$conn->close();
?>