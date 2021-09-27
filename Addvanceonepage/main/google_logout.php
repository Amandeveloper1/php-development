<?php
include('config.php');
require 'connection.php';

echo "Logout are prosesing";
$token = $_SESSION['token'];
$updatequrey = "UPDATE `signup` SET `status` = 'Inactive' WHERE `signup`.`token` = '$token';";
$update = mysqli_query($conn, $updatequrey);

if ($update) {
    $google_client->revokeToken();
    session_destroy();
    $massage = 'You are logout successfully, go login us.';
    $valuemassage = 'success';
    header("location:/Addvanceonepage/home.php?massage=$massage&valuemassage=$valuemassage");
} else {
    echo "This is not work.";
}

?>