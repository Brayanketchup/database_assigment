<?php
   include("dbconfig.php");
   //get connection and desconect it
   $home_page = 'index.html';
    $con = mysqli_connect($db_hostname,$db_username,$db_password,$db_name);
   mysqli_close($con);
   echo "<br>You successfully logout<br>";
   echo"<br> <a href='$home_page'>project home page</a>";

?>