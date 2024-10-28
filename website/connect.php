<?php

$con=new mysqli('localhost','root','myfirstdatabase');

if($con){
    echo "connection successful";
}else{
    die(mysqli_error($con));
}


?>