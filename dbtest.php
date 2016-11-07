<?php
include '../password.php';
require 'vendor/autoload.php';
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mp1-db',
]);
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
print_r($result);
print "============\n". $endpoint . "================\n";
$conn = mysqli_connect($endpoint,"controller1","radhika6") or die("Error " . mysqli_error($link)); 
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
	}
$sql = "CREATE DATABASE IF NOT EXISTS school";
if ($conn->query($sql) === TRUE) {
echo "Database created successfully";
} else {
echo "Error creating database: " . $conn->error;
}
$conn->close();
echo "<br>";
$conn= mysqli_connect($endpoint,"controller1","radhika6","school");
$sql1 = "CREATE TABLE IF NOT EXISTS student ( id INT(6) AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), age INT(3))";
if ($conn->query($sql1) === TRUE) {
echo "Table created successfully";
} else {
echo "Error creating database: " . $conn->error;
}
echo "<br>";
$sql3 = "INSERT INTO `student` (`name`,`age`) VALUES ('Rads',24),('Prince',100),('abhishek',40),('Zeenia',26),('timcy',23)";
if ($conn->query($sql3) === TRUE) {
echo "Data inserted successfully";
} else {
echo "Error creating database: " . $conn->error;
}
$conn->close();
$link->real_query("SELECT * FROM students");
$res = $link->use_result();
echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
    echo $row['ID'] . " " . $row['name']. " " . $row['age'];
}
?>