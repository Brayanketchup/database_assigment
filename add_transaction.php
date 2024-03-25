<?php
include("dbconfig.php");
$con = mysqli_connect($db_hostname,$db_username,$db_password,$db_name)
    or die("<br>Cannot connect to DB");
   
    $User_id=$_GET["customer_id"];
    $logout = 'logout.php';
    $insert_transaction = 'insert_transaction.php';

    $fetch_user_name = "SELECT name FROM CPS3740.Customers WHERE id = '$User_id'";
    $result_name_fetch = mysqli_query($con, $fetch_user_name);
    $name_exist = mysqli_num_rows($result_name_fetch);
    if ($name_exist>0) {
        while($row = mysqli_fetch_array($result_name_fetch)){
            $User_name = $row["name"];
        }
    }

    $sql_money = "SELECT * from CPS3740_2023S.Money_martbray where cid = '$User_id'";
      $result_sql_money = mysqli_query($con, $sql_money);
      $Transactions = mysqli_num_rows($result_sql_money);
      $balance = 0;
      
      if($Transactions>0){
          while($row = mysqli_fetch_array($result_sql_money)){
          $transaction_type = $row["type"];
          $all_transactions = $row["amount"];
          $User_transaction_sid = $row["sid"];
          if ($transaction_type=='D'){
              $balance = ($balance + $all_transactions);
          }else{
              $balance = ($balance - $all_transactions);
          }
          //translate source from numerical to a word
          $sql_Sourse_code = "SELECT name FROM CPS3740.Sources where id = '$User_transaction_sid'";
          $sql_sourse = mysqli_query($con, $sql_Sourse_code);
          while($row = mysqli_fetch_array($sql_sourse)){
          $Source_name = $row["name"];
      }
    }
}

    echo"<br><a href='$logout'>User logout</a>\n";
    echo"<br>";
    echo"<br><font size=4><b>Add Transaction</b></font>"
      . "<br><b>$User_name</b> current balance is <b>$balance</b>."
      . "<br><form name='input' action='$insert_transaction' method='get' required='required'>"
      . "<input type='hidden' name='customer_id' value='$User_id'>"
      . "Transaction code: <input type='text' name='code' required='required'>"
      . "<br><input type='radio' name='type' value='D'>Deposit"
      . "<input type='radio' name='type' value='W'3>Withdraw"
      . "<br> Amount: <input type='text' name='amount' required='required'><input type='hidden' name='customer_id' value='$User_id'>"
      . "<br>Select a Source: <SELECT name='source_id'>"
      . "<option value=''></option>
        <option value=1>ATM</option>
        <option value=2>Online</option>
        <option value=3>Branch</option>
        <option value=4>Wired</option>
        <option value=5>New11</option>
        <option value=6>Mobile</option>
        </SELECT>"
      . "<BR>Note: <input type='text' name='note'> "
      ."<br><input type='submit' value='Submit'></form></form>";
      
?>