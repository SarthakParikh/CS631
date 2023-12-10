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


// Fetch data for dropdowns
$speciesResult = $conn->query("SELECT * FROM species");
$buildingResult = $conn->query("SELECT * FROM building where type = 'zoo'");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentStatus = $_POST['current_status'];
    $birthYear = $_POST['birth_year'];
    $speciesId = $_POST['species_id'];
    $BuildingID = $_POST['building_id'];
    $cageId = $_POST['cage_id'];


    echo $currentStatus, $birthYear, $speciesId,$cageId,$BuildingID;
    // Insert data into the database
    $sql = "INSERT INTO animal (status, birthyear, SID, BID, ENID) VALUES ('$currentStatus', '$birthYear', '$speciesId', '$BuildingID','$cageId')";

    if ($conn->query($sql) === TRUE) {
        echo "Record added successfully";
        header("Location: animal.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Data Form</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
<nav>
    <a href='../index.html'>Home</a>
   <a href='../asset_mgmt.html'>Asset Management</a>
 
</nav>
    <h2>Add Data to Database</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="current_status">Current Status:</label>
        
        <select  name="current_status" required>
        <option value = 'healthy'>healthy  </option>
        <option value = 'retired'>Retired  </option>
        <option value = 'maternal leave'>maternal leave  </option>
        <option value = 'under medical care'>under medical care  </option>
        <option value = 'New Born'>New Born  </option>

        </select>
        
        
        
        <br><br>

        <label for="birth_year">Birth Year:</label>
        <input type="text" name="birth_year" placeholder="xxxx" required><br><br>

        <label for="species_id">Species:</label>
        <select name="species_id" required>
            <?php while ($row = $speciesResult->fetch_assoc()) : ?>
                <option value="<?php echo $row['SID']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="building_id">Building:</label>
        <select name="building_id" id="building_id" onchange="updateCageDropdown()" required>
            <?php while ($row = $buildingResult->fetch_assoc()) : ?>
                <option value="<?php echo $row['BID']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="cage_id">Cage:</label>
        <select name="cage_id" id="cage_id" required>
            <!-- Cage options will be dynamically populated -->
        </select><br><br>

        <input type="submit" value="Submit">
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
// Close the database connection
$conn->close();
?>
