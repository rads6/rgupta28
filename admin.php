<?php 
include 'nav.php';
include '../password.php';


if ($user != "admin"){
  header("Location: gallery.php");
} else {
//Insert into table:
  $mysqli = new mysqli($_SESSION["hostname"],$username,$password,"app");

  
$admin = 'CREATE TABLE IF NOT EXISTS admin_main
(
  id INT NOT NULL AUTO_INCREMENT,
  page VARCHAR(50) NOT NULL UNIQUE,
  status INT(1) NULL,
  PRIMARY KEY(id)
)';
$result = $mysqli->query($admin);

$chk_adm = "SELECT * FROM admin_main";
$check_admin = $mysqli->query($chk_adm);

$row_cnt = $check_admin->num_rows;

if ($row_cnt == 0) { 

$insert = 'INSERT INTO admin_main (`id`,`page`,`status`) VALUES (1,"upload",1)';

$insert_usr = $mysqli->query($insert);
}
$up = "SELECT * FROM admin_main WHERE feature='upload'";

$result = $mysqli->query($up);
$res = $result->fetch_assoc();
$upload = $res['status'];


?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>

</style>
  </head>
  <body>
    <br><br><br>
<div class="background">
<div class="transbox">
    <div class="jumbotron text-center">
    <h1>Admin Feature</h1> 
<center>
<form class="form-inline" action="#" method="post">
<h4>Upload Feature:
<input type="radio" name="upload"
<?php if ($upload == '1') echo "checked ";?>
value="1">On
<input type="radio" name="upload"
<?php if ($upload == '0') echo "checked ";?>
value="0">Off</h4>
<input class="btn btn-danger" type="submit" value="uploadSubmit">
</form></center>
<br><br>
<center>
<form class="form-inline" action="#" method="post">
<input class="btn btn-danger" type='submit' value='DUMP DATABASE' name='sqlsubmitbutton'>
</form></center>
<br><br>
<center>
<form action="#" method="POST" enctype="multipart/form-data">
Select File:<input type="file" name="fileToUpload" id="fileToUpload">
<input class="btn btn-danger" type='submit' value='RESTORE DATABASE' name='sqlupload'>
</form></center

<?php
if ( isset($_POST['sqlupload'])) {

$target_dir = "/tmp/";
$target_file = $target_dir . "database.sql";// basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";
        $db = "app";
    $command = "mysql --host={$end} --user={$username} --password={$password} --database={$db} < $target_file";
   echo $command;
   exec($command);
      //exec ("mysql  --user={$username} --password={$password} --database=app < $target_file");
      //echo $cmd;
      //echo $target_file;
      //exec($cmd);
} 
}

if ( isset($_POST['sqlsubmitbutton'])) {
  $file="/tmp/database.sql";
  exec( "mysqldump -h $end -u $username --password=$password app > $file");
  
  if (file_exists($file))
    {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exec( " > $file");
    exec( "rm $file");
    exit;
    }
}

if ( isset($_POST['uploadSubmit'])) {
  $fea = $_POST['upload'];
  echo $fea;
  //UPDATE table_name SET column1=value1,column2=value2,... WHERE some_column=some_value;
$up = "UPDATE admin SET status=$fea WHERE feature='upload'";

$result = $mysqli->query($up);
}
$mysqli->close();
}
?>
 </div></div></div>
</body>
</html>

