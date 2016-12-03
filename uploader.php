<?php
session_start();
require 'vendor/autoload.php';
$s3 = new Aws\S3\S3Client([
 'version' => 'latest',
 'region' => 'us-west-2'
]);
// have to hard code this here because index.php doesn't exist
$_SESSION['email']=$_SESSION['userid'];
echo "\n Welcome to Uploader Page \n" . $_SESSION['email'] ."\n<br>";
// To upload the file and giving temporary name.
$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
$_SESSION['objectname']=$uploadfile;
#echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
 echo "<br>File is valid, and was successfully uploaded.\n<br>";
} else {
 echo "<br>Possible file upload attack!\n";
}
echo '<br>Here is some more debugging info:<br>';
print_r($_FILES);
// To push the file into the bucket
$s3result = $s3->putObject([
 'ACL' => 'public-read',
 'Bucket' => 'raw-rad',
 'Key' => basename($_FILES['userfile']['name']),
 'SourceFile' => $uploadfile
// Retrieve URL of uploaded Object
]);
$url=$s3result['ObjectURL'];
echo "\n". "This is your URL: " . $url ."\n<br>";
//Insert sql information
$rdsclient = new Aws\Rds\RdsClient([
 'region' => 'us-west-2',
 'version' => 'latest'
]);
$rdsresult = $rdsclient->describeDBInstances([
 'DBInstanceIdentifier' => 'clouddatabases'
]);
//$endpoint = $rdsresult['DBInstances'][0]['Endpoint']['Address'];
//echo $endpoint . "\n";
$link = mysqli_connect("clouddatabases.clbbdifdgtxm.us-west-2.rds.amazonaws.com:3306","controller1","radhika6","school") or die("Error " . mysqli_error($link));
/* check connection */
if (mysqli_connect_errno()) {
 printf("Connect failed: %s\n", mysqli_connect_error());
 exit();
}
?>
