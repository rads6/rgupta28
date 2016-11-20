<?php
session_start();
echo $_POST['username'];

$name = $_POST['username'];
echo " Welcome ..!! Your name is . $name";
 $_SESSION['username'] = $_POST['username'];
?>




<html>
<head><title>Welcome!</title>
</head>
<body>
<H1  align="center">Choose from menu options what you want to do</H1>
<ul>
  <li><a href="gallery.php.asp">Gallery</a></li>
  <li><a href="upload.php">Upload</a></li>
  <li><a href="admin.php">Admin</a></li>
  
</ul>

</body>
</html>

