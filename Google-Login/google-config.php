<?php
include_once 'src/google/Google_Client.php';
include_once 'src/google/contrib/Google_Oauth2Service.php';


$clientId = '88519530897-qh3inh2th5vfun9niqp79psgk07745gv.apps.googleusercontent.com'; //Google client ID
$clientSecret = 'nM5LbQoRJLrXdnXO1MeXyzDj'; //Google client secret
$gRedirectURL = 'http://www.mytesttodo.me/google-login.php'; //Callback URL

$gClient = new Google_Client();
$gClient->setApplicationName('Mytesttodo');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($gRedirectURL);
$gClient->setAccessType('online');
$gClient->setApprovalPrompt('auto') ;
$google_oauthV2 = new Google_Oauth2Service($gClient);
$gloginUrl = $gClient->createAuthUrl();
?>