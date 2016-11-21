

<?php
require 'vendor/autoload.php';

$client = new Aws\Rds\RdsClient([
  'region'            => 'us-west-2',
    'version'           => 'latest'
]);
 
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
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


$sql = "CREATE TABLE records
(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(32),
phone VARCHAR(32),
s3-raw-url VARCHAR(32),
s3-finished-url VARCHAR(32),
status INT(1),
receipt VARCHAR(256)
)";
$create_tbl = $link->query($sql);
if ($sql) {
echo "Table is created or No error returned.";
}
else {
        echo "error!!";  
}
$link->close();
?>
