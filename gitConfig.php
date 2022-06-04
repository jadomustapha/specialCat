<?php
// Start session
if(!session_id()){
    session_start();
}

// Include Github client library 
require_once 'Github_OAuth_Client.php';


/*
 * Configuration and setup GitHub API
 */
$clientID         = '73829da29f0313c891e1';
$clientSecret     = 'b81903a478f912bb0fd747e9abc0b0c4c606217a';
// $redirectURL     = 'http://localhost/signWithGithub/';
$redirectURL     = 'https://www.codexworld.com/github-login/';

$gitClient = new Github_OAuth_Client(array(
    'client_id' => $clientID,
    'client_secret' => $clientSecret,
    'redirect_uri' => $redirectURL,
));


// Try to get the access token
if(isset($_SESSION['access_token'])){
    $accessToken = $_SESSION['access_token'];
}