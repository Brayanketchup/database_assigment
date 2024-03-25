<?php

include("dbconfig.php");

$con = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("<br>Cannot connect to DB");

//get values from previous page
$cid = $_GET["customer_id"];
$logout = 'logout.php';
$update_transaction = 'update_transaction.php';

echo "<br><a href='$logout'>User logout</a>\n";
echo "<br>You can only update <b>Note</b> column.";
echo "<form action='$update_transaction' method='get'>";


//sql to retrieve everything from martbray
$sql_money = "SELECT * from CPS3740_2023S.Money_martbray where cid = '$cid'";
$result_sql_money = mysqli_query($con, $sql_money);
$Transactions = mysqli_num_rows($result_sql_money);

//variables needed for storing balance and looping porpuses
$balance = 0;
$i=0;
//arrays to store all the information retrieved
$customer_id = array();
$source_id = array();
$mid = array();
$cnote = array();
$cdelete = array();

//if there are any transactions then execute the table
if ($Transactions > 0) {
    //printing table
    echo "<TABLE border=1>\n";
    echo "<TR><TH>ID<TH>Code<TH>Type<TH>Amount<TH>Source<TH>Data Time<TH>Note<TH>Delete";
    //retrive all the values from the other page and store them into the new arrays
    while ($row = mysqli_fetch_array($result_sql_money)) {
        //cust info
        $customer_id[$i] = $row["cid"];
        $source_id[$i] = $row["sid"];
        $mid[$i] = $row["mid"];
        $cnote[$i] = $row["note"];
        $cdelete[$i] = $row[$i];
        $User_transaction_code = $row["code"];
        $User_transaction_type = $row["type"];
        $User_transaction_amount = $row["amount"];
        $User_transaction_date = $row["mydatetime"];

        //if deposit add, else subtract
        if ($User_transaction_type == 'D') {
            $transaction_type = 'Deposit';
            $balance = ($balance + $User_transaction_amount);
            $display_amount = "<font color='blue'>$User_transaction_amount</font>";
        } else {
            $balance = ($balance - $User_transaction_amount);
            $transaction_type = 'Withdraw';
            $display_amount = "<font color='red'>-$User_transaction_amount</font>";
        }
        
        //translate source from numerical to a word
        $sql_source_num = "SELECT name FROM CPS3740.Sources where id = '$source_id[$i]'";
        $result_source_num = mysqli_query($con, $source_num);
        while ($row = mysqli_fetch_array($result_source_num)) {
            $Source_name = $row["name"];
        }
        
        echo"<input type='hidden' name='customer_id[$i]' value='$customer_id[$i]'>"
        ."<input type='hidden' name='source_id[$i]' value='$source_id[$i]'>"
        ."<input type='hidden' name='mid[$i]' value='$mid[$i]'>";
        echo "<TR><TD>$mid[$i]<TD>$User_transaction_code<TD>$transaction_type<TD>$display_amount<TD>$Source_name<TD>$User_transaction_date<TD bgcolor='yellow'><input type='text' value='' name='cnote[$i]' style='background-color:yellow;'></TD><TD><input type='checkbox' name='cdelete[$i]' value='Y'></TD>\n";
        $i++;
    }
    echo "</TABLE>\n";
    echo "Total balance:  <font color='blue'>$balance</font>\n"
    . "<br><input type='submit' value='Update transaction'>"
    . "<br></form>";
} else {
    echo "<br>No record found for customer: $cid";
}

mysqli_close($con);
?>