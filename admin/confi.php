<?php

$servername = "localhost";

$username = "root"; 

$password = ""; 

$dbname = "vishalfood"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn){

    //echo "connection ok";

}
else{

    echo "connection failed".mysqli_connect_error();
}


?> 