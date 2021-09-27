<?php
$server = 'localhost';
$username = 'root';
$password = '';
$datebase = 'addvancepage';

$conn = mysqli_connect($server,$username,$password,$datebase);
if(!$conn){
    echo'Your datebase are not connect.';
}

?>