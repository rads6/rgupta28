<?php

// make sure you have php-gd installed and you may need to reload the webserver (apache2)

// get SQS queue name 

// query to see if any messages

//if so then retreive the body of the first queue message and assign it to a variable 

// look up the RDS database instance name (URI)

// Query the RDS database:  SELECT * FROM records WHERE RECEIPT = $receipt;

$rawurl = $Row['rawurl'];


// load the "stamp" and photo to apply the water mark to
$stamp = imagecreatefrompng('IIT-logo.png');  // grab this locally or from an S3 bucket probably easier from an S3 bucket...
$im = imagecreatefromjpeg('workers.jpg');  // replace this path with $rawurl

//Set the margins for the stamp and get the height and width of the stamp image
$marge_right=10;
$marge_bottom=10;
$sx = imagesx($stamp);
$sy = imagesy($stamp);
echo $sy . "\n";

//Copy the stamp image onto our photo using the margin offsets and the photo 
// width to calculate positioning of the stamp
imagecopy($im,$stamp,imagesx($im) - $sx -$marge_right, imagesy($im) - $sy -$marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

//output and free memory
//header('Content-type: image/png');
imagepng($im,'/tmp/rendered.png');
imagedestroy($im);

// place the rendred image into S3 finished-url bucket
// retreive the Object URL
// update the ROW in the RDS database - change the status to 1 (finished) and add the S3finshedURL

// Consume the message on the Queue (delete/consume it)

// Send SNS notification to the customer of succeess.

// Profit.

?>
