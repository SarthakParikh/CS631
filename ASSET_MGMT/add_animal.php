<?php
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

function getDropdownOptions($conn, $tableName, $columnName) {
    $options = array();
    // SELECT DISTINCT `BID` FROM enclosure;
    $sql = "SELECT  $columnName FROM $tableName";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $options[] = $row[$columnName];
        }
    }

    return $options;
}










function getDropdownOptions_building($conn, $tableName, $columnName) {
    $options = array();
    // SELECT DISTINCT `BID` FROM enclosure;
    $sql = "SELECT DISTINCT $columnName FROM $tableName";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $options[] = $row[$columnName];
        }
    }

    return $options;
}










function getCageOptionsByBuilding($conn, $building) {
    $options = array();
    $building = sanitize($conn, $building);

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT ENID FROM enclosure WHERE BID = ?");
    $stmt->bind_param("s", $building);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $options[] = $row['ENID'];
        }
    }

    $stmt->close();

    return $options;
}






function loadData($conn, &$speciesOptions, &$buildingOptions, &$cageOptions) {
    // Get options for dropdowns
    $speciesOptions = getDropdownOptions($conn, 'species', 'SID');
    $buildingOptions = getDropdownOptions_building($conn, 'enclosure', 'BID');

    // Initialize cage options with the first building
    $selectedBuilding = reset($buildingOptions);
    $cageOptions = getCageOptionsByBuilding($conn, $selectedBuilding);

    // Handle building selection
    if (isset($_POST['building_id'])) {
        $selectedBuilding = sanitize($conn, $_POST['building_id']);
        $cageOptions = getCageOptionsByBuilding($conn, $selectedBuilding);
    }
}

// Initialize variables
$birth_year = $current_status = $species_id = $building_id = $cage_id = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    // Get form data and sanitize inputs
    $birth_year = sanitize($conn, $_POST["birth_year"]);
    $current_status = sanitize($conn, $_POST["current_status"]);
    $species_id = sanitize($conn, $_POST["species_id"]);
    $building_id = sanitize($conn, $_POST["building_id"]);
    $cage_id = sanitize($conn, $_POST["cage_id"]);


    echo $cage_id,$species_id,$building_id;
    $sql = "INSERT INTO animal (status, birthyear, SID, BID, ENID)
    VALUES ('$current_status', '$birth_year', '$species_id', '$building_id', '$cage_id');
    ";
    
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
        header("Location: animal.php");

    } else {
        echo "Error updating record: " . $conn->error;
    }
}

loadData($conn, $speciesOptions, $buildingOptions, $cageOptions);
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

    <form method="post">

        <label for="birth_year">Birth Year:</label>
        <input type="number" min={4} max={4} name="birth_year" id="birth_year" value="<?= htmlspecialchars($birth_year) ?>" required><br>

        <label for="current_status">Current Status:</label>
        <input type="text" name="current_status" id="current_status" value="<?= htmlspecialchars($current_status) ?>" required><br>

        <label for="species_id">Species:</label>
        <select name="species_id" id="species_id">
            <?php foreach ($speciesOptions as $option) : ?>
                <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="building_id">Building:</label>
        <select name="building_id" id="building_id" onchange="this.form.submit()">
            <?php foreach ($buildingOptions as $option) : ?>
                <option value="<?= htmlspecialchars($option) ?>" <?php if ($building_id == $option) echo 'selected'; ?>>
                    <?= htmlspecialchars($option) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="cage_id">Cage:</label>
        <select name="cage_id" id="cage_id">
            <?php foreach ($cageOptions as $option) : ?>
                <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
            <?php endforeach; ?>
        </select><br>

        <input type="submit" name="update" value="Update">

    </form>

</body>

</html>
