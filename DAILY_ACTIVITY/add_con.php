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

$query1 = "SELECT rid, name FROM `revenue_type` WHERE type='concession';";
$result_att = $conn->query($query1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $attractionID = $_POST["cage_id"]?? null;
    
    $current_date = date("Y-m-d");
    $current_time = date("H:i:s");

    // Insert the new attraction into the database
    $query_rev = "SELECT Price FROM concession WHERE Product='$attractionID'";
    $result_rev = $conn->query($query_rev);

    $resultString = "";

    while ($row = $result_rev->fetch_assoc()) {
        $resultString = $row['Price'];

    }
 

    $query12 = "SELECT RID  FROM concession WHERE Product='$attractionID'";
    $result_att1 = $conn->query($query12);
    $RID ='';
    while ($row = $result_att1->fetch_assoc()) {
        $RID = $row['RID'];


    }


   $tckSold = 0;
   echo $resultString;
   
    // $insertQuery = "INSERT INTO revenue_event (RID, Date, Time ,Revenue,ticketsold) VALUES ('$RID', '$current_date','$current_time', '$resultString','$tckSold')";
    // if ($conn->query($insertQuery) === TRUE) {
    //     echo "Attraction added successfully!";
    //     header("Location: concessions.php");

    // } else {
    //     echo "Error: " . $insertQuery . "<br>" . $conn->error;
    // }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy from Concession</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
    <h2>Buy from Concession</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Shop Name:

    <select name="building_id" id="building_id" onchange="updateCageDropdown()" required>
            <?php while ($row = $result_att->fetch_assoc()) : ?>
                <option value="<?php echo $row['rid']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br><br>
    Product Name:
    <select name="cage_id" id="cage_id" required>
            <!-- Cage options will be dynamically populated -->
        </select><br><br>

        <input type="submit" value="Buy item">
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
                        cageDropdown.append('<option value="' + value.Product + '">' + value.Product + '</option>');
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