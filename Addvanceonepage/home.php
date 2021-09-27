<?php

if (isset($_GET['set'])) {
    setcookie("favirate_produce", "icecream");
}
if (isset($_GET['delete'])) {
    setcookie("favirate_produce", null, time() - 86400);
}

require 'main/connection.php';
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $username = $_GET['username'];
    $Otp = $_GET['otp'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $userotp = mysqli_real_escape_string($conn, $_POST['otp']);
    $token = bin2hex(random_bytes(15));

    if (isset($_POST['g-recaptcha-response'])) {
        $recaptcha = $_POST['g-recaptcha-response'];
        if (!$recaptcha) {
            $massage = 'Your are not chack recapcha, please check recapcha.';
            $valuemassage = 'danger';
            header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
            exit;
        } else {
            $secret = "6LdLA9sbAAAAAA7k5aDRAImsAMR_u9oi5xqL2qyT";
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $recaptcha;
            $response = file_get_contents($url);
            $responseKeys = json_decode($response, true);

            if ($responseKeys['success']) {

                if ($Otp == $userotp) {
                    $password = password_hash($password, PASSWORD_BCRYPT);
                    $sql = "INSERT INTO `signup` (`sno`, `username`, `email`, `password`, `token`, `status`, `datetime`)
                     VALUES (NULL, '$username', '$email', '$password', '$token', 'Inactive', current_timestamp());";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        $massage = 'You are signup successfully, go login us.';
                        $valuemassage = 'success';
                        header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                    } else {
                        echo 'not inserted.';
                    }
                } else {
                    $massage = 'Enter the Vaild Otp please.';
                    $valuemassage = 'danger';
                    header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                }
            } else {
                $massage = 'Please fill all requriment.';
                $valuemassage = 'danger';
                header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
            }
        }
    }
}

include('main/config.php');
if (isset($_GET["code"])) {

    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

    if (!isset($token['error'])) {

        $google_client->setAccessToken($token['access_token']);
        $_SESSION['access_token'] = $token['access_token'];
        $google_service = new Google_Service_Oauth2($google_client);
        $data = $google_service->userinfo->get();

        if (!empty($data['given_name'])) {
            $_SESSION['user_first_name'] = $data['given_name'];
        }

        if (!empty($data['family_name'])) {
            $_SESSION['user_last_name'] = $data['family_name'];
        }

        if (!empty($data['picture'])) {
            $_SESSION['user_image'] = $data['picture'];
        }

        if (!empty($data['email'])) {
            $_SESSION['user_email_address'] = $data['email'];

            $email = $_SESSION['user_email_address'];
            $sql = "SELECT * FROM `signup` WHERE email='$email'";
            $result = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);

            if ($num == 0) {
                $username = $_SESSION['user_first_name'] . $_SESSION['user_last_name'];
                $token = bin2hex(random_bytes(15));
                $passwordg = "Google_user";
                $password = password_hash($passwordg, PASSWORD_BCRYPT);

                $sql = "INSERT INTO `signup` (`sno`, `username`, `email`, `password`, `token`, `status`, `datetime`)
                 VALUES (NULL, '$username', '$email', '$password', '$token', 'Inactive', current_timestamp());";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $massage = 'You are signup successfully, go login us.';
                    $valuemassage = 'success';
                    header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                } else {
                    echo 'not inserted.';
                }
            } elseif ($num == 1) {

                $email = $_SESSION['user_email_address'];
                $sql = "SELECT * FROM `signup` WHERE email='$email'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                $token = $row['token'];
                $updatequrey = "UPDATE `signup` SET `status` = 'Active' WHERE `signup`.`token` = '$token'";
                $update = mysqli_query($conn, $updatequrey);

                if ($update) {
                    session_start();
                    $_SESSION['google_login'] = true;
                    $_SESSION['login'] = true;
                    $_SESSION['active'] = true;
                    $_SESSION['token'] = $token;
                    $massage = 'You are login successfully, emjoy us.';
                    $valuemassage = 'success';
                    header("location:/Addvanceonepage/home.php?token=$token&massage=$massage&valuemassage=$valuemassage");
                }
            }
        }
    }
}

$signup_botton = '<button type="submit" class="btn btn-danger"><a href="' . $google_client->createAuthUrl() . '">Signup With Google</a></button>';
$login_botton = '<button type="submit" class="btn btn-danger"><a href="' . $google_client->createAuthUrl() . '">Login With Google</a></button>';

?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" contant="This wedsite help for programer his start a jourany.">
    <meta name="author" content="Aman Gupta">
    <meta name="keywords" contant="wed developer,wed programing, full stark wed developer,fast optimaisation">
    <meta name="robots" content="INDEX, FOLLOW">
    <!-- this a addvance meta tags./ -->
    <meta property="og:title" content="Amancoder">
    <meta property="og:description" content="This all about as of amancoder.">
    <meta property="og:image" content="here are link of the image.">

    <!-- they are all link  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="shortcut icon" href="photos/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/home.css">

    <title>Addvanceonepage Favirate</title>
</head>
<style>
    .icon {
        display: flex;
    }

    .iconss {
        margin-left: -61px;
        margin-top: 6px;
        cursor: pointer;
    }

    .white {
        color: white;
    }

    .con {
        width: 25%;
        margin-top: -355px;
        margin-left: 14px;
        position: sticky;
        bottom: 10px;
    }

    .cook {
        border-radius: 18px;
    }

    @media Only screen and (max-width :1344px) {}
    
    .pro {
        border: 2px solid;
        width: 30%;
        margin-top: 25px;
        padding: 10px;
    }

    .imgs {
        border: 2px solid;
        width: 245px;
        height: 235px;
    }

    .images {
        text-align: center;
    }

    .sat {
        color: #0b0bf1;
        text-align: right;
        cursor: pointer;
    }

    .sat:hover {
        color: #acace4;
    }

    .bas {
        margin-top: -33px;
    }
</style>

<body>

    <!-- navbar stick  -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand color" href="#">My Wedsite</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active color" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active color" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active color" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link color" href="download.php">Download</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <?php
    if (isset($_GET['massage'])) {
        $massage  = $_GET['massage'];
        $valueMassage = $_GET['valuemassage'];
        echo '<div class="alert alert-' . $valueMassage . ' alert-dismissible fade show mb-0" role="alert">
        <strong>Addvancewedsite!</strong>' . $massage . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
    if (isset($_SESSION['login'])) {
        $login = $_SESSION['login'];
        $token = $_SESSION['token'];
    }
    ?>

    <!-- background of wedsite theam -->
    <div class="background">
        <img src="photos/1.jpg" alt="">
        <div class="passage font ">
            <h1 class="fs-1" id="rotated">Welcome to our Wedsite</h1>
            <p>Thank you for visit the wedsite i wish your statisty to the wedsite.</p>
            <button type="link" id="veiw" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#passModal">Veiw more</button>
            <?php
            if (isset($login)) {
                echo '  <a class="btn btn-outline-primary" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">Account</a>';
            } ?>
        </div>
    </div>

    <!-- login & signup botton  -->
    <div class="container signupbottom">
        <?php
        if (!isset($login)) {

            echo ' <button class="btn btn-outline-primary mx-2" data-bs-toggle="modal" data-bs-target="#loginModal" type="submit">Login</button>
                   <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#signupModal" type="submit">Signup</button>';
        } else {
            if (isset($_SESSION['google_login'])) {
                echo '<button class="btn btn-outline-primary mx-2" type="submit"><a href="main/logout.php?token=' . $token . '">Logout</a></button>';
            } else {
                echo '<button class="btn btn-outline-primary mx-2" type="submit"><a href="main/logout.php?token=' . $token . '">Logout</a></button>';
            }
        }
        ?>
    </div>

    <!-- off convas  -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Account</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
        <div class="sat">
            <h4 onclick="show()" class="text-right">Edit</h4>
        </div>
            <div>
                <h1>Hi User,</h1> <br>
                Thank you for login in the wedsite and enjoy us.
            </div>
            <?php echo '<form action="main/user_profile.php?token='.$token.'" method="POST" enctype="multipart/form-data">';?>
            
                <div class="mb-3" id="imginfo">
                    <label class="form-label">Select your image</label>
                    <input name="img" type="file">
                </div>
                <?php
                $sql = "SELECT * FROM `userimage` WHERE token='$token'";
                $result = mysqli_query($conn, $sql);
                $num = mysqli_num_rows($result);
                if ($num == 0) {
                    echo '
                    <div class="images">
                        <img class="imgs" src="photos/person.png" alt="">
                    </div>';
                } else {
                    $row = mysqli_fetch_assoc($result);
                    $sto = $row['story'];
                    $userimg = $row['image'];
                    echo '<div class="container ms-4">
                    <div class="images">
                            <img class="imgs" src="main/'.$userimg.'" alt="">
                        </div>
                        <div class="best">' . $sto . '</div> </div> ';
                }
                ?>

                <div class="form-floating my-2" id="storys">
                    <textarea class="form-control" placeholder="Leave a comment here" name="story" id="floatingTextarea2" style="height: 100px"></textarea>
                    <label for="floatingTextarea2">Enter Your story</label>
                </div>

                <button type="submit" id="submitup" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login Here</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="container">
                    <h5 class="modal-title text-center">Login for an Account</h5>
                </div>
                <div class="container text-center mt-2">
                    <?php echo $login_botton; ?>
                </div>
                <div class="container text-center">
                    <h5 class="modal-title">Or</h5>
                </div>
                <div class="modal-body">
                    <form action="main/login.php" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Enter Email</label>
                            <input type="text" class="form-control" id="lemail" name="email">
                            <div id="namevalid" class="invalid-feedback">Enter valid Email.</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Enter Password</label>
                            <div class="icon">
                                <input type="password" class="form-control" id="lpassword" name="password">
                                <span onclick="show()" id="visibal" class="iconss material-icons">visibility</span>
                                <span onclick="hide()" id="invisibal" class="iconss material-icons">visibility_off</span>
                            </div>
                            <div id="namevalid" class="invalid-feedback">Enter the your password.</div>
                        </div>
                        <div class="ca">
                            <div class="g-recaptcha" data-sitekey="6Lep9tobAAAAAI1HOn01_yBqEaPLbFbZwq_b-eCk"></div>
                            <input type="submit" id="lsubmit" name="submit" class="btn btn-primary">
                            <!-- <button type="submit" id="lsubmit" class="btn btn-primary m-2">Login Now</button> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Signup Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Signup Here</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="container">
                    <h5 class="modal-title text-center">Signup for an Account</h5>
                </div>
                <div class="container text-center mt-2">
                    <?php echo $signup_botton; ?>
                </div>
                <div class="container text-center">
                    <h5 class="modal-title">Or</h5>
                </div>
                <div class="modal-body">
                    <form action="main/mailer.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">UserName</label>
                            <input type="text" class="form-control" id="susername" name="username">
                            <div id="namevalid" class="invalid-feedback">Username must be 2-10 charter are require.</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="semail" name="email" aria-describedby="emailHelp">
                            <div id="namevalid" class="invalid-feedback">Please Enter a valid email.</div>
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div id="ca" class="ca">
                            <button type="submit" id="osubmit" class="btn btn-primary m-2">Sent OTP</button>
                        </div>
                    </form>
                    <hr>
                    <form class="mt-4" method="POST">
                        <div class="mb-3">
                            <label for="otp" class="form-label">Enter OTP</label>
                            <input type="number" class="form-control" id="sotp" name="otp">
                            <div id="namevalid" class="invalid-feedback">Please Enter otp ,sended in you email.</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Create Password</label>
                            <div class="icon">
                                <input type="password" class="form-control" id="spassword" name="password" aria-describedby="emailHelp">
                                <span onclick="sshow()" id="svisibal" class="iconss material-icons">visibility</span>
                                <span onclick="shide()" id="sinvisibal" class="iconss material-icons">visibility_off</span>
                            </div>
                            <div id="namevalid" class="invalid-feedback">please enter up of 4 chacter.</div>
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div id="submitBotton">
                            <div class="g-recaptcha" data-sitekey="6LdLA9sbAAAAAJWQyoMllw2xvLqLv_trEcNm5Sas"></div>
                            <input type="submit" id="ssubmit" name="submit" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- member information -->
    <section class="text-gray-600 body-font">
        <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-wrap w-full mb-20">
                <div class="lg:w-1/2 w-full mb-6 lg:mb-0">
                    <h1 class="sm:text-3xl text-2xl font-medium title-font mb-2 text-gray-900">Pitchfork Kickstarter
                        Taxidermy</h1>
                    <div class="h-1 w-20 bg-indigo-500 rounded"></div>
                </div>
                <p class="lg:w-1/2 w-full leading-relaxed text-gray-500">Whatever cardigan tote bag tumblr hexagon
                    brooklyn asymmetrical gentrify, subway tile poke farm-to-table. Franzen you probably haven't heard
                    of them man bun deep jianbing selfies heirloom prism food truck ugh squid celiac humblebrag.</p>
            </div>
            <div class="flex flex-wrap -m-4">
                <div class="xl:w-1/4 md:w-1/2 p-4" data-aos-duration="1000">
                    <div class="bg-gray-100 p-6 rounded-lg">
                        <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://source.unsplash.com/1600x900/?great,company" alt="content">
                        <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">SUBTITLE</h3>
                        <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Chichen Itza</h2>
                        <p class="leading-relaxed text-base">Fingerstache flexitarian street art 8-bit waistcoat.
                            Distillery hexagon disrupt edison bulbche.</p>
                    </div>
                </div>
                <div class="xl:w-1/4 md:w-1/2 p-4" data-aos="fade-right" data-aos-duration="1000">
                    <div class="bg-gray-100 p-6 rounded-lg">
                        <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://source.unsplash.com/1600x900/?member" alt="content">
                        <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">SUBTITLE</h3>
                        <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Colosseum Roma</h2>
                        <p class="leading-relaxed text-base">Fingerstache flexitarian street art 8-bit waistcoat.
                            Distillery hexagon disrupt edison bulbche.</p>
                    </div>
                </div>
                <div class="xl:w-1/4 md:w-1/2 p-4" data-aos="fade-left" data-aos-duration="1000">
                    <div class="bg-gray-100 p-6 rounded-lg">
                        <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://source.unsplash.com/1600x900/?employee" alt="content">
                        <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">SUBTITLE</h3>
                        <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Great Pyramid of Giza</h2>
                        <p class="leading-relaxed text-base">Fingerstache flexitarian street art 8-bit waistcoat.
                            Distillery hexagon disrupt edison bulbche.</p>
                    </div>
                </div>
                <div class="xl:w-1/4 md:w-1/2 p-4" data-aos-duration="1000">
                    <div class="bg-gray-100 p-6 rounded-lg">
                        <img class="h-40 rounded w-full object-cover object-center mb-6" src="https://source.unsplash.com/1600x900/?worker" alt="content">
                        <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">SUBTITLE</h3>
                        <h2 class="text-lg text-gray-900 font-medium title-font mb-4">San Francisco</h2>
                        <p class="leading-relaxed text-base">Fingerstache flexitarian street art 8-bit waistcoat.
                            Distillery hexagon disrupt edison bulbche.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require 'main/footer.php'; ?>

    <div id="dismisshow" class="con" data-aos="fade-right" data-aos-duration="1000">
        <div class="card text-center bg-dark cook" role="alert" style="width: 18rem;">
            <img src="photos/cos.jpg" class="card-img-top cook" alt="...">
            <div class="card-body">
                <h3 class="white">Cookies Apceted</h3>
                <p class="card-text white">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <button class="btn btn-outline-success" id="dismiss" onclick="dis()">DISMISS</button>
                <button id="apceted" class="btn btn-outline-success" onclick="apce()">APCETED</button>
            </div>
        </div>
    </div>
    <?php
    if (isset($_COOKIE['favirate_produce'])) {
    ?><script>
            dismisshow.style.display = 'none';
        </script>
    <?php echo '<button class="btn btn-outline-success" ><a href="home.php?delete=sets">delete_cookies</a></button>';
    } ?>
    <script src="js/home.js"></script>

    <script>
        let visibal = document.getElementById('visibal');
        let invisibal = document.getElementById('invisibal');
        visibal.style.display = 'block';
        invisibal.style.display = 'none';

        function show() {
            lpassword.setAttribute('type', 'text');
            visibal.style.display = 'none';
            invisibal.style.display = 'block';
        }

        function hide() {
            lpassword.setAttribute('type', 'password');
            visibal.style.display = 'block';
            invisibal.style.display = 'none';
        }

        let svisibal = document.getElementById('svisibal');
        let sinvisibal = document.getElementById('sinvisibal');
        svisibal.style.display = 'block';
        sinvisibal.style.display = 'none';

        function sshow() {
            spassword.setAttribute('type', 'text');
            svisibal.style.display = 'none';
            sinvisibal.style.display = 'block';
            console.log('this is show.');
        }

        function shide() {
            spassword.setAttribute('type', 'password');
            svisibal.style.display = 'block';
            sinvisibal.style.display = 'none';
            console.log('this is hide.');
        }

        function dis() {
            let dismiss = document.getElementById('dismisshow');
            dismiss.style.display = 'none';
        }

        function apce() {
            window.location = 'home.php?set=set';
        }
        let imginfo = document.getElementById('imginfo');
        let submitup = document.getElementById('submitup');
        let storys = document.getElementById('storys');

        imginfo.style.display = 'none';
        storys.style.display = 'none';
        submitup.style.display = 'none';

        function show() {
            imginfo.style.display = 'block';
            storys.style.display = 'block';
            submitup.style.display = 'block';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

</body>

</html>