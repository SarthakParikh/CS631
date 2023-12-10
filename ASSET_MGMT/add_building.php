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


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentStatus = $_POST['current_status'];
    $birthYear = $_POST['birth_year'];
   



    // Insert data into the database
    $sql = "INSERT INTO building (name, type) VALUES ('$currentStatus', '$birthYear')";

    if ($conn->query($sql) === TRUE) {
        echo "Record added successfully";
        header("Location: buildind.php");
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
        <label for="current_status">NAME :</label>
        <input type="text" name="current_status" required><br><br>

 



        <label for="birth_year">TYPE:</label>
     

        <select  name="birth_year" required>
        <option value = 'zoo'>zoo  </option>
        <option value = 'concession'>concession  </option>
        <option value = 'admission'>admission  </option>


        </select>
        <br><br>
        <input type="submit" value="Submit">
    </form>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
