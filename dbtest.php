<?php

require 'vendor/autoload.php';


$client = new Aws\Rds\RdsClient([
  'region'            => 'us-west-2',
    'version'           => 'latest'
]);
 

$result = $client->describeDBInstances([
    'DBInstanceIdentifier' => 'rg-db'
]);


$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
echo $endpoint . "\n";

$link = mysqli_connect($endpoint,"controller1","radhika6","school") or die("Error " . mysqli_error($link));

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}



$create_table = 'CREATE TABLE IF NOT EXISTS student 
(
    id INT NOT NULL AUTO_INCREMENT,
   name VARCHAR(255),
   age INT(3) ,
    PRIMARY KEY(id)
)';



$create_tbl = $link->query($create_table);
if ($create_table) {
echo "Table is created or No error returned.";
}
else {
        echo "error!!";  
}
$link->close();
?>

