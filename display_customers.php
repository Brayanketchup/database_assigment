<?php
include("dbconfig.php");
$con = mysqli_connect($db_hostname,$db_username,$db_password,$db_name)
    or die("<br>Cannot connect to DB");

    $sql = "SELECT * from CPS3740.Customers";
    $result = mysqli_query($con, $sql);

    $num=mysqli_num_rows($result);
    if ($num>0) {
    $logout = 'logout.php';
    $add_transaction = 'add_transaction.php';
    $search_transaction = 'search_transaction.php';
    $display_transaction = 'display_transaction.php';

    echo "<TABLE border=1>\n";
    echo "<TR><TH>ID<TH>login<TH>password<TH>Name<TH>Gender<TH>DOB<TH>Street<TH>City<TH>State<TH>Zipcode";
    //collection of all the user's information
    while($row = mysqli_fetch_array($result)){
        
        $User_id = $row["id"];
        $User_name = $row["name"];
        $User_login = $row["login"];
        $User_password = $row["password"];
        $User_DOB = $row["DOB"];
        $User_gender = $row["gender"];
        $User_img= $row["img"];
        $User_street = $row["street"];
        $User_city = $row["city"];
        $User_state = $row["state"];
        $User_zipcode = $row["zipcode"];

        echo "<TR><TD>$User_id<TD>$User_login<TD>$User_password<TD>$User_name<TD>$User_gender<TD>$User_DOB<TD>$User_street<TD>$User_city<TD>$User_state<TD>$User_zipcode\n";
    }
    
}else{echo"<br>There is not Customers";}

?>