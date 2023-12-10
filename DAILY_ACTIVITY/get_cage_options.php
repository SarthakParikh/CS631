<?php
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

// Fetch cage options based on the selected building
$buildingId = $_POST['building_id'];
$query = $conn->prepare("SELECT * FROM concession WHERE RID = ?");
$query->bind_param("i", $buildingId);
$query->execute();
$result = $query->get_result();

// Store cage options in an array
$cageOptions = array();
while ($row = $result->fetch_assoc()) {
    $cageOptions[] = $row;
}

// Return the cage options as JSON
echo json_encode($cageOptions);

// Close the database connection
$conn->close();
?>
