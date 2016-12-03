<html>
<head><H1> Welcome To Radhika's Gallery</H1></head>
<style type="text/css">
body{
background:url('https://s3-us-west-2.amazonaws.com/raw-rad/cloud.jpg');
}

form{
width: 400px;
margin:0 auto 0 auto;

}
</style>

<div style="float: center; width:400x; text-align: center;">
<form action="upload.php">
<button>Upload</button>
</form>
</div>

<div style="float: left; width:400px;">
<form action="index.php">
<button>logout</button>
</form>
</div>
</div>

<div style="float:left ; width: 400px">

<form action="admin.php">
<button>admin</button>
</form>
</div>



<?php

session_start();

echo " <b>Hi " .  $_SESSION['userid'];
$email = $_SESSION['email'];
//echo "\n<br>" . md5($email) . "<br>";

$_SESSION['receipt'] = md5($email);

require 'vendor/autoload.php';


$rdsclient = new Aws\Rds\RdsClient([
  'region'            => 'us-west-2',
    'version'           => 'latest'
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

$link->real_query("SELECT * FROM records");

$res = $link->use_result();
echo "<br>Result set order...\n<br>";

while ($row = $res->fetch_assoc()) {
echo "ID = \n". $row['id'] . "Status = \n" . $row['status'] . "This is raw image" . "<br>";
    echo "<img src =\" " . $row['s3rawurl'] . "\" />" . "<br>";
//echo "ID = \n". $row['id'] . "This is finished image with status 1" . "<br>";

//echo "<img src =\"" . $row['s3finishedurl'] . "\"/>" . "<br>";
//    echo "<img src =\" " . "This is the raw image " . $row['s3rawurl'] . "\" /><img src =\"" . "This is the finished image" . $row['s3finishedurl'] . "\"/>";

}

require 'vendor/autoload.php';
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

$s3result = $s3->getObject([
'Bucket' => 'raw-rad',
'Key' => $_SESSION['objectname'] ,
]);

echo $s3result;

$link->close();


?>
<?php
if ($_SESSION['userid']!="rgupta28@hawk.iit.edu")
{
?>

<html>
<head><body>

<form action="upload.php">
<button>Upload</button>
</form>

</head></body></html>

<?php
}
?>
