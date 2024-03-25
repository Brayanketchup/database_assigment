<?php
include("dbconfig.php");
$con = mysqli_connect($db_hostname,$db_username,$db_password,$db_name)
    or die("<br>Cannot connect to DB");

    $User_id=$_GET["customer_id"];
    $insert_code=$_GET["code"];
    $insert_type=$_GET["type"];
    $insert_amount=$_GET["amount"];
    $insert_source=$_GET["source_id"];
    $insert_note=$_GET["note"];


    $sql_money = "SELECT * from CPS3740_2023S.Money_martbray where cid = '$User_id'";
      $result_sql_money = mysqli_query($con, $sql_money);
      $Transactions = mysqli_num_rows($result_sql_money);
      $balance = 0;
      
      //get the balance
      if($Transactions>0){
          while($row = mysqli_fetch_array($result_sql_money)){
          $User_transaction_type = $row["type"];
          $User_transaction_amount = $row["amount"];
          if ($User_transaction_type=='D'){
              $balance = ($balance + $User_transaction_amount);}else{
              $balance = ($balance - $User_transaction_amount);
          }
        }
    }

    //get costumer name
    $fetch_customer_name = "SELECT name FROM CPS3740.Customers WHERE id = '$User_id'";
    $result_name_fetch = mysqli_query($con, $fetch_customer_name);
    $name_exist = mysqli_num_rows($result_name_fetch);
    if ($name_exist>0) {
        while($row = mysqli_fetch_array($result_name_fetch)){
            $customer_name = $row["name"];
        }
    }

    $w_handler = ($balance - $insert_amount);
    $source_check = (isset($insert_source) && !empty($insert_source));

    if($insert_type == "W"){
        $new_balance = $w_handler;
    }else{
        $new_balance = ($balance + $insert_amount);
    }

    $sql_code_check = "SELECT code from CPS3740_2023S.Money_martbray where cid = '$User_id' AND code = '$insert_code'";
    $result_sql_code = mysqli_query($con, $sql_code_check);
    $code_exist = mysqli_num_rows($result_sql_code);

    
    //check if the code is unique, 
    //check if it's a withdraw check that the balance is grater, 
    //check if the amount input if empty, or is not and integer or if is not positive
    //if all it get's to this point then insert the new transaction at Money_martbray
    if($code_exist>0){echo"<br><font color=red>Error! There is same transaction code in database.</font>";
    }else{
        if($insert_type == "W" && $w_handler<0){echo"<br><font color=red>Error! Customer $customer_name has $balance in the bank, and tries to withdraw $insert_amount. Not enough money!</font>";
        }else{
            if(empty($insert_amount) || (is_int($insert_amount) || $insert_amount<1)){ echo"<br><font color=red>Amount must be positive.</font>";
            }else{
                if($source_check){
                    $sql_insert_row = "INSERT INTO Money_martbray VALUES( null, '$insert_code', '$User_id', '$insert_type', $insert_amount, now(), '$insert_note','$insert_source')";   
                    $result_sql_insertion = mysqli_query($con, $sql_insert_row);
                    echo"<br>Successfully add the transcation<br>New balance: $new_balance";
                }
            }
        }
    }

?>