<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    session_start();
    $token = $_SESSION['token'];
}
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $story = $_POST['story'];
    $file = $_FILES['img'];

    $filename = $file['name'];
    $filepath = $file['tmp_name'];
    $fileerror = $file['error'];

    $sql = "SELECT * FROM `userimage` WHERE token='$token'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);

    $massage = 'Something went a worng, please try again.';
    $valuemassage = 'danger';
    
    if ($num == 0) {

        if ($fileerror == 0) {
            $filedest = 'userimages/' . $filename;
            move_uploaded_file($filepath, $filedest);

            $sql = "INSERT INTO `userimage` (`sno`, `story`, `image`, `token`)
         VALUES (NULL, '$story', '$filedest', '$token')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $massage = 'Your story and profile created successfully.';
                $valuemassage = 'success';
                header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
            }
        }
    } elseif (!empty($story) && !empty($filename)) {

        $row = mysqli_fetch_assoc($result);
        $token = $row['token'];
        $deletefile = $row['image'];

        $sql = "UPDATE `userimage` SET `story` = '$story' WHERE `userimage`.`token` =  '$token'";
        $result = mysqli_query($conn, $sql);

        if ($result) {

            $filename = $file['name'];
            $filepath = $file['tmp_name'];
            $fileerror = $file['error'];

            if ($fileerror == 0) {
                $deletes = unlink($deletefile);
                $filedest = 'userimages/' . $filename;
                move_uploaded_file($filepath, $filedest);

                $sql = "UPDATE `userimage` SET `image` = '$filedest' WHERE `userimage`.`token` =  '$token'";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $massage = 'Your story and profile are update successfully.';
                    $valuemassage = 'success';
                    header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                }
            }
        }
    } elseif (!empty($filename)) {

        $sql = "SELECT * FROM `userimage` WHERE token='$token'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);
        $token = $row['token'];
        $deletefile = $row['image'];

        $filename = $file['name'];
        $filepath = $file['tmp_name'];
        $fileerror = $file['error'];

        if ($fileerror == 0) {
            $deletes = unlink($deletefile);
            $filedest = 'userimages/' . $filename;
            move_uploaded_file($filepath, $filedest);

            $sql = "UPDATE `userimage` SET `image` = '$filedest' WHERE `userimage`.`token` =  '$token'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $massage = 'Your profile are update successfully.';
                $valuemassage = 'success';
                header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
            }
        }
    } elseif (!empty($story)) {

        $sql = "SELECT * FROM `userimage` WHERE token='$token'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);

        $row = mysqli_fetch_assoc($result);
        $token = $row['token'];
        $sql = "UPDATE `userimage` SET `story` = '$story' WHERE `userimage`.`token` =  '$token'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $massage = 'Your story are update successfully.';
            $valuemassage = 'success';
            header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
        }
    }

    header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
}
