<?php
require 'connection.php';
if (isset($_GET['token'])) {
    $token = $_GET['token'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (isset($_POST['g-recaptcha-response'])) {
        $recaptcha = $_POST['g-recaptcha-response'];
        if (!$recaptcha) {
            $massage = 'Please chack the box.';
            $valuemassage = 'danger';
            header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
            exit;
        } else {
            $secret = "6Lep9tobAAAAAOWq7aU0pCwMwIfBNCUHaU-0Fmm4";
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $recaptcha;
            $response = file_get_contents($url);
            $responseKeys = json_decode($response, true);

            if ($responseKeys['success']) {

                if (isset($token)) {
                    $sql = "SELECT * FROM `signup` WHERE token='$token'";
                    $result = mysqli_query($conn, $sql);
                    $num = mysqli_num_rows($result);

                    if ($num == 1) {
                        $row = mysqli_fetch_assoc($result);
                        if (password_verify($password, $row['password'])) {
                            $token = $row['token'];
                            $updatequrey = "UPDATE `signup` SET `status` = 'Active' WHERE `signup`.`token` = '$token'";
                            $update = mysqli_query($conn, $updatequrey);

                            if ($update) {
                                session_start();
                                $_SESSION['login'] = true;
                                $_SESSION['active'] = true;
                                $_SESSION['token'] = $token;
                                
                                $massage = 'You are login successfully successfully.';
                                $valuemassage = 'success';
                                header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                            }
                        } elseif($num == 0) {
                            $massage = 'This is invalid, please to first signup us .';
                            $valuemassage = 'danger';
                            header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                        }else {
                            $massage = 'Something went worng.';
                            $valuemassage = 'danger';
                            header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                        }
                    }else {
                        $massage = 'This email are invalid go.';
                        $valuemassage = 'danger';
                        header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                    }
                } else {
                    $sql = "SELECT * FROM `signup` WHERE email='$email'";
                    $result = mysqli_query($conn, $sql);
                    $num = mysqli_num_rows($result);

                    $row = mysqli_fetch_assoc($result);
                    if ($num == 1) {
                        if (password_verify($password, $row['password'])) {
                            $token = $row['token'];
                            $updatequrey = "UPDATE `signup` SET `status` = 'Active' WHERE `signup`.`token` = '$token'";
                            $update = mysqli_query($conn, $updatequrey);

                            if ($update) {
                                session_start();
                                $_SESSION['login'] = true;
                                $_SESSION['active'] = true;
                                $_SESSION['token'] = $token;
                                $massage = 'You are login successfully, emjoy us.';
                                $valuemassage = 'success';
                                header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                            }
                        } else {
                            $massage = 'Your password is not match, please try again.';
                            $valuemassage = 'danger';
                            header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                        }
                    } elseif($num == 0) {
                        $massage = 'This is invalid, please to first signup us .';
                        $valuemassage = 'danger';
                        header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                    }else {
                        $massage = 'Something went worng.';
                        $valuemassage = 'danger';
                        header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                    }
                }

            } else {
                $massage = 'Something went worng.';
                $valuemassage = 'danger';
                header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
            }
        }
    }
}
