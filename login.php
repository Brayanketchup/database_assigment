<?php
echo "<HTML>\n";

include("dbconfig.php");


$con = mysqli_connect($db_hostname,$db_username,$db_password,$db_name)
    or die("<br>Cannot connect to DB");


$login= mysqli_real_escape_string( $con,$_GET["login"]);

$password=mysqli_real_escape_string($con, $_GET["password"]); 
 
$sql= "SELECT * FROM CPS3740.Customers WHERE login = '$login' AND password='$password'";
$sql_login = "SELECT login FROM CPS3740.Customers WHERE login = '$login'";

$result = mysqli_query($con, $sql);
$login_result = mysqli_query($con, $sql_login);


$login_exist = mysqli_num_rows($login_result);
// setcookie("login",$login,time()+3600);
// echo "<br>Cookie: " . $_COOKIE["login"] ."\n";
$num=mysqli_num_rows($result);
if ($num>0) {
    $logout = 'logout.php';
    $add_transaction = 'add_transaction.php';
    $search_transaction = 'search_transaction.php';
    $display_transaction = 'display_transaction.php';

    //collection of all the user's information
    while($row = mysqli_fetch_array($result)){
        $cid = $row["id"];
        $customerName = $row["name"];
        $DOB = $row["DOB"];
        $customer_image= $row["img"];
        $customerStreet = $row["street"];
        $customerCity = $row["city"];
        $customerZipcode = $row["zipcode"];
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $age = date_diff(date_create($DOB), date_create('today'))->y;
    
    echo"<br><a href='$logout'>User logout</a>\n";
    echo"<br>Your IP: $ip\n";
    echo"<br>your broser info: $user_agent\n";
    #split token
    $IPv4= explode(".",$ip);
    ///* Project Hint : complete if-else statement here.
        if($IPv4[0]=='10' || $IPv4[0]=='131' && $IPv4[0]=='125'){
            
            echo "<br>You are from Kean University.\n"; 
        }else{echo"<br>You are Not from kean Univers\n";}
    echo"<br>Welcome Customer: <strong>$customerName\n</strong><br>age: $age\n<br>Address: $customerStreet, $customerCity, $customerZipcode\n";
    echo"<br><img src='data:image/jpeg;base64," . base64_encode( $customer_image ) ."'/>\n";
    echo"<br><hr>";
        
        //money information
    $sql_money = "SELECT * from CPS3740_2023S.Money_martbray where cid = '$cid'";
    $result_sql_money = mysqli_query($con, $sql_money);
    $Transactions = mysqli_num_rows($result_sql_money);
    $balance = 0;
    if($Transactions>0){
        echo"There are <Strong>$Transactions</Strong> transcations for customer <Strong>$customerName</Strong>:";
        echo "<TABLE border=1>\n";
        //table header with tittle
        echo "<TR><TH>ID<TH>Code<TH>Type<TH>Amount<TH>Source<TH>Data Time<TH>Note";
        //print table
        while($row = mysqli_fetch_array($result_sql_money)){
        $mid = $row["mid"];
        $code = $row["code"];
        $cid = $row["cid"];
        $type = $row["type"];
        $amount = $row["amount"];
        $date = $row["mydatetime"];
        $note = $row["note"];
        $source_id = $row["sid"];

        //adjust balance
        if ($type=='D'){
            $transaction_type = 'Deposit';
            $balance = ($balance + $amount);
            $display_amount = "<font color='blue'>$amount</font>";
        }else{
            $transaction_type = 'Withdraw';
            $balance = ($balance - $amount);
            $display_amount = "<font color='red'>-$amount</font>";
        }
        //translate source from numerical to a word
        $Translate_code = "SELECT name FROM CPS3740.Sources where id = '$source_id'";
        $User_transaction_sourse = mysqli_query($con, $Translate_code);
        while($row = mysqli_fetch_array($User_transaction_sourse)){
        $Source_name = $row["name"];}

        echo "<TR><TD>$mid<TD>$code<TD>$transaction_type<TD>$display_amount<TD>$Source_name<TD>$date<TD>$note\n";
        }
        echo "</TABLE>\n";
        echo"Total balance:  <font color='blue'>$balance</font>\n";


        //if the customer does not have any records display a message:
    }else{
        echo"<br>No record found for customer: $customerName";
    }
    //close table and send all the information to the next page
    echo"<br><table border='0'>";
        echo"<br><tbody><tr><td><form action='$add_transaction' method='GET'>\n"
        . "<input type='hidden' name='customer_id' value='$cid'\n>"
        . "<input type='submit' value='Add transaction'></form>"
        . "</td><td><a href='display_transaction.php?customer_id=$cid'>Display and update transaction</a>"
        . "</td></tr><tr><td colspan='2'><form action='$search_transaction' method='get'>"
        . "Keyword: <input type='text' name='keyword' required='required'>"
        . "<input type='hidden' name='customer_id' value='$cid'>"
        . "<input type='submit' value='Search transaction'></form>"
        . "</td></tr></tbody></table>";
}
//check if login exist
else if ($login_exist=1){
    echo"<br>Login $login exist, but password not matches\n";
    mysqli_close($con);
}else {
    echo"<br> Login $login does not exist in database";
    mysqli_close($con);
}
?> 
