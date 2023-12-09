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



$sql_building = "SELECT * FROM building";
$result_building = $conn->query($sql_building);




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentStatus = $_POST['current_status'];
    $birthYear = $_POST['building_name'];
    $type = $_POST['type'];
   



    // Insert data into the database
      $sql = "INSERT INTO revenue_type (name, type,BID) VALUES ('$currentStatus', '$type','$birthYear')";

    if ($conn->query($sql) === TRUE) {
        echo "Record added successfully";
        header("Location: attractions.php");
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
    <h2>Add Data to Database</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="current_status">NAME :</label>
        <input type="text" name="current_status" required><br>
        Building Name:
    <select name="building_name">
        <?php
        // Populate dropdown with building names
        while($row_building = $result_building->fetch_assoc()) {
            echo "<option value='" . $row_building["BID"] . "'>" . $row_building["name"] . "</option>";
        }
        ?>
    </select><br>
    TYPE
    <select name="type">
      
     
    <option value = "COncession"> Concession  </option>
     
            <option value = "Animal Show"> Animal Show </option>
       
    </select><br>

        <input type="submit" value="Submit">
    </form>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
