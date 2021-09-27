<?php
//start session on web page
session_start();

//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('1017221478284-f14ceaukth63scl7c22hv1kru2mapteq.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('yvJqQdVZ5kc0EIjkql19xf3d');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/Addvanceonepage/home.php');

// to get the email and profile 
$google_client->addScope('email');

$google_client->addScope('profile');

?>