<?php
include("dbconfig.php");

$con = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("<br>Cannot connect to DB");

$customer_id = array();
$source_id = array();
$mid = array();
$cnote = array();
$cdelete = array();
$i=0;
$updated=0;
$deleted =0;

foreach($_GET['customer_id'] as $cid) {
  $customer_id[$i] = $cid;
  $source_id[$i] = $_GET['source_id'][$i];;
  $mid[$i] = $_GET['mid'][$i];
  $cnote[$i] = $_GET['cnote'][$i];

  if(isset($_GET['cdelete'][$i]) ){  
    $sql_mid_delete = "DELETE FROM Money_martbray WHERE mid = '$mid[$i]'";   
    $result_sql_delete = mysqli_query($con, $sql_mid_delete);
    $deleted++;
    echo"<br>$sql_mid_delete Successfully delete transaction code: $sql_mid_delete";
  }

  if(isset($cnote[$i]) && !empty($cnote[$i])){
 $sql_row_check = "SELECT * from CPS3740_2023S.Money_martbray where mid = '$mid[$i]'";
      $result_sql_check = mysqli_query($con, $sql_row_check);
      $row_exist = mysqli_num_rows($result_sql_check);
      if($row_exist>0){
        $sql_mid_note = "UPDATE Money_martbray set note = '$cnote[$i]',mydatetime=now() WHERE mid='$mid[$i]' AND note !='$cnote[$i]'";
      $result_sql_note = mysqli_query($con, $sql_mid_note);
      echo"<br>Successfully update transaction code: $sql_mid_note";
      $updated++;
      }
    }
    $i++;

}
  echo"<br> Finish deleting $deleted records and updating $updated transactions";

   

?>