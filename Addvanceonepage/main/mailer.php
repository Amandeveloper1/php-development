<?php
require "connection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $emailqurey = " SELECT * FROM `signup` WHERE email='$email'";
    $qurey = mysqli_query($conn, $emailqurey);
    $emailcount = mysqli_num_rows($qurey);
    $Otp = rand(10000, 100000);

    if ($emailcount == 0) {

        $subject = "Account Verification.";
        $body = "Hi, $username. This is otp of the email.
             $Otp";
        $senderemail = "From: AmanScureloginsyste";
        $sender = mail($email, $subject, $body, $senderemail);

        if ($sender) {
            $massage = 'Email are sented successfully to email chack email or enter the otp.';
            $valuemassage = 'success';
            header("location:/Addvanceonepage/home.php?email=$email&username=$username&otp=$Otp&massage=$massage&valuemassage=$valuemassage");
        } else {
            echo "Email sending failed...";
        }
    } else {
        $massage = 'This email are alredy extied, go to login us.';
        $valuemassage = 'danger';
        header("location:/Addvanceonepage/home.php?massage=$massage&valuemassage=$valuemassage");
    }
}
