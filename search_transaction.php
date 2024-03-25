<?php
include("dbconfig.php");
$con = mysqli_connect($db_hostname,$db_username,$db_password,$db_name)
    or die("<br>Cannot connect to DB");

    $keywrd=$_GET["keyword"];
    $User_id=$_GET["customer_id"];

    if($keywrd === '*'){
        $keywrd === '';
    }
    
    $sql_keyword = "SELECT * from CPS3740_2023S.Money_martbray where cid = '$User_id' and note LIKE '%$keywrd%'";
    $result_sql_money = mysqli_query($con, $sql_keyword);
    $Transactions = mysqli_num_rows($result_sql_money);
    $balance = 0;


    $fetch_user_name = "SELECT name FROM CPS3740.Customers WHERE id = '$User_id'";
    $result_name_fetch = mysqli_query($con, $fetch_user_name);
    $name_exist = mysqli_num_rows($result_name_fetch);
    if ($name_exist>0) {
        while($row = mysqli_fetch_array($result_name_fetch)){
            $User_name = $row["name"];
        }
    }
    
    if($Transactions>0){
        echo"There are <Strong>$Transactions</Strong> transcations for customer <Strong>$User_name</Strong>:";
        echo "<TABLE border=1>\n";
        echo "<TR><TH>ID<TH>Code<TH>Type<TH>Amount<TH>Source<TH>Data Time<TH>Note";
        while($row = mysqli_fetch_array($result_sql_money)){
        $User_transaction_mid = $row["mid"];
        $User_transaction_code = $row["code"];
        $User_transaction_cid = $row["cid"];
        $User_transaction_type = $row["type"];
        $User_transaction_amount = $row["amount"];
        $User_transaction_date = $row["mydatetime"];
        $User_transaction_note = $row["note"];
        $User_transaction_sid = $row["sid"];
        if ($User_transaction_type=='D'){
            $balance = ($balance + $User_transaction_amount);
        }else{
            $balance = ($balance - $User_transaction_amount);
        }
        //translate source from numerical to a word
        $Translate_code = "SELECT name FROM CPS3740.Sources where id = '$User_transaction_sid'";
        $User_transaction_sourse = mysqli_query($con, $Translate_code);
        while($row = mysqli_fetch_array($User_transaction_sourse)){
        $Source_name = $row["name"];}

        echo "<TR><TD>$User_transaction_mid<TD>$User_transaction_code<TD>$User_transaction_type<TD><font color='blue'>$User_transaction_amount</font><TD>$Source_name<TD>$User_transaction_date<TD>$User_transaction_note\n";
        }
        echo "</TABLE>\n";
        echo"Total balance:  <font color='blue'>$balance</font>\n";
    }else{
        echo"<br>No record found for customer: $User_name";
    }
    
?>