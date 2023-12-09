

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "turtleback"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
   
    $wage = $_POST["wage"];

    // Insert data into the "employees" table
    $sql = "INSERT INTO hourly_rate ( Rate) VALUES ('$wage')";

    if ($conn->query($sql) === TRUE) {
        echo "Record added successfully";
        header("Location: hourly_wages.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>