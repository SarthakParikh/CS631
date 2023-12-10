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

$animal_id = $birth_year = $current_status = $species_id = $cage_id = $building_id = $monthly_food_cost = $acts_name = $vet_id = $acts_id = '';

$speciesResult = $conn->query("SELECT * FROM species");
$buildingResult = $conn->query("SELECT * FROM building where type = 'zoo'");




if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id = sanitize($conn, $_POST["id"]);
    $current_status = sanitize($conn, $_POST["current_status"]);
    $birth_year = sanitize($conn, $_POST["birth_year"]);
    $species_id = sanitize($conn, $_POST["species_id"]);
    $cage_id = sanitize($conn, $_POST["cage_id"]);
    $building_id = sanitize($conn, $_POST["building_id"]);


echo $species_id,$building_id,$cage_id;



    // Update data in the database
    $sql = "UPDATE animal SET status = '$current_status', BirthYear = '$birth_year', ENID = '$cage_id', BID = '$building_id' , SID='$species_id'WHERE AID = '$id';";
    if ($conn->query($sql) === true) {
        echo "Record updated successfully";
        header("Location: animal.php");

    } else {
        echo "Error updating record: " . $conn->error;
    }
}

if (isset($_GET["id"])) {
    $id = sanitize($conn, $_GET["id"]);

    $sql = "SELECT A.AID, A.status, A.BirthYear, A.SID, A.ENID, A.BID, FC.food_cost FROM Animal A JOIN species FC ON A.SID = FC.SID WHERE AID='$id'";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $animal_id = $row["AID"];
        $current_status = $row["status"];
        $birth_year = $row["BirthYear"];
        $species_id = $row["SID"];
        $cage_id = $row["ENID"];
        $building_id = $row["BID"];
        $monthly_food_cost = $row["food_cost"];
        
        
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
<nav>
    <a href='../index.html'>Home</a>
   <a href='../asset_mgmt.html'>Asset Management</a>
 
</nav>
<body>

<form method="post">
    <label for="animal_id">Animal ID:</label>
    <input type="text" name="id" id="id" value="<?= htmlspecialchars($id) ?>" readonly>

    <label for="birth_year">Birth Year:</label>
    <input type="text" name="birth_year" id="birth_year" value="<?= htmlspecialchars($birth_year) ?>" required><br>

    <label for="current_status">Current Status:</label>
    <input type="text" name="current_status" id="current_status" value="<?= htmlspecialchars($current_status) ?>" required><br>



    <br>



<label for="species_id">Species:</label>
        <select name="species_id" id="species_id"  required>
            <?php while ($row = $speciesResult->fetch_assoc()) : ?>
                <option value="<?php echo $row['SID']; ?>"><?php echo $row['SID']; ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="building_id">Building:</label>
        <select name="building_id" id="building_id"  onchange="updateCageDropdown()" required>
            <?php while ($row = $buildingResult->fetch_assoc()) : ?>
                <option value="<?php echo $row['BID']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="cage_id">Cage:</label>
        <select name="cage_id" id="cage_id" value="<?= htmlspecialchars($cage_id) ?>" >
            <!-- Cage options will be dynamically populated -->
        </select><br><br>










    <label for="monthly_food_cost">Monthly Food Cost:</label>
    <input type="text" name="monthly_food_cost" id="monthly_food_cost" value="<?= isset($monthly_food_cost) ? htmlspecialchars($monthly_food_cost) : 'Hola' ?>" readonly><br>

    <input type="submit" name="update" value="Update">
   
</form>
<script>
        function updateCageDropdown() {
            var buildingId = $("#building_id").val();
            var cageDropdown = $("#cage_id");

            // Clear existing options
            cageDropdown.empty();

            // Fetch cage options based on the selected building using AJAX
            $.ajax({
                type: "POST",
                url: "get_cage_options.php", // Create a separate PHP file to handle this AJAX request
                data: { building_id: buildingId },
                dataType: "json",
                success: function(data) {
                    // Populate cage options
                    $.each(data, function(index, value) {
                        cageDropdown.append('<option value="' + value.ENID + '">' + value.ENID + '</option>');
                    });
                }
            });
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
