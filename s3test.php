<?php
echo"Hello World";
 
require 'vendor/autoload.php';
 
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
 
#$s3 = $sdk->createS3();
$result = $s3->listBuckets();
 
foreach ($result['Buckets'] as $bucket) {
    echo $bucket['Name'] . "\n";
}
 
$key = 'switchonarex.png';
$result = $s3->putObject(array(
'ACL'=>'public-read',
'Bucket'=>'raw-rads',
'Key' => $key,
'SourceFile'=> '/var/www/html/switchonarex.png'
));
$url=$result['ObjectURL'];
echo $url;
 
?>
 
<!DOCTYPE html>
<html>
<body>
 
<h2>Hello</h2>
<img src="https://s3-us-west-2.amazonaws.com/raw-rads/switchonarex.png" alt="IIT">
 
</body>
</html>