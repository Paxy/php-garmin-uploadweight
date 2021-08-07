<?php

$username="YourUsername";
$password="YourPassword";

require 'vendor/autoload.php';
use GuzzleHttp\Client;

error_reporting(E_ALL);
ini_set('display_errors', '1');

if(!isset($_GET['weight'])) return;
$weight=$_GET['weight'];
if(!is_numeric($weight)) return;

$jar = new \GuzzleHttp\Cookie\CookieJar();
$agent='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36';


$client = new Client([
    'base_uri' => 'https://sso.garmin.com/',
    'cookies' => $jar
]);

$response=$client->request('GET', 'sso/signin',[
    'headers' => [
        'User-Agent'      => $agent,
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' =>  'gzip, deflate',
        'origin'=> 'https://sso.garmin.com'
        ],
     'query' => [   
            'webhost'=> 'https://connect.garmin.com',
            'service'=> 'https://connect.garmin.com',
            'source'=> 'https://sso.garmin.com/sso/signin',
            'redirectAfterAccountLoginUrl'=> 'https://connect.garmin.com',
            'redirectAfterAccountCreationUrl'=> 'https://connect.garmin.com',
            'gauthHost'=> 'https://sso.garmin.com/sso',
            'locale'=> 'en_US',
            'id'=> 'gauth-widget',
            'cssUrl'=> 'https://static.garmincdn.com/com.garmin.connect/ui/css/gauth-custom-v1.2-min.css',
            'clientId'=> 'GarminConnect',
            'rememberMeShown'=> 'true',
            'rememberMeChecked'=> 'false',
            'createAccountShown'=> 'true',
            'openCreateAccount'=> 'false',
            'usernameShown'=> 'false',
            'displayNameShown'=> 'false',
            'consumeServiceTicket'=> 'false',
            'initialFocus'=> 'true',
            'embedWidget'=> 'false',
            'generateExtraServiceTicket'=> 'false'
     ], 
//    'debug' => true,
//    'version' => 1.0
]);


$response = $client->request('POST', 'sso/signin', [
    'headers' => [
        'User-Agent'      => $agent,
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' =>  'gzip, deflate',
        'origin'=> 'https://sso.garmin.com',
        ],
    'form_params' => [
            'username'=> $username,
            'password'=> $password,
            'embed'=> 'true',
            'lt'=> 'e1s1',
            '_eventId'=> 'submit',
            'displayNameRequired'=> 'false'
        ],
     'query' => [   
            'webhost'=> 'https://connect.garmin.com',
            'service'=> 'https://connect.garmin.com',
            'source'=> 'https://sso.garmin.com/sso/signin',
            'redirectAfterAccountLoginUrl'=> 'https://connect.garmin.com',
            'redirectAfterAccountCreationUrl'=> 'https://connect.garmin.com',
            'gauthHost'=> 'https://sso.garmin.com/sso',
            'locale'=> 'en_US',
            'id'=> 'gauth-widget',
            'cssUrl'=> 'https://static.garmincdn.com/com.garmin.connect/ui/css/gauth-custom-v1.2-min.css',
            'clientId'=> 'GarminConnect',
            'rememberMeShown'=> 'true',
            'rememberMeChecked'=> 'false',
            'createAccountShown'=> 'true',
            'openCreateAccount'=> 'false',
            'usernameShown'=> 'false',
            'displayNameShown'=> 'false',
            'consumeServiceTicket'=> 'false',
            'initialFocus'=> 'true',
            'embedWidget'=> 'false',
            'generateExtraServiceTicket'=> 'false'
     ],    
//    'debug' => true,
    
]);
$body=$response->getBody();
$pos = strpos($body, "ticket=")+7;
$end = strpos($body, "\"", $pos+1);
$ticket=substr($body,$pos,$end-$pos);
//echo $body;



$client = new Client([
    'base_uri' => 'https://connect.garmin.com',
    'cookies' => $jar
]);

$response=$client->request('GET', '/',[
    'headers' => [
        'User-Agent'      => $agent,
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' =>  'gzip, deflate',
        'origin'=> 'https://sso.garmin.com'
        ],
    'query' => [ 'ticket' => $ticket ],  
//    'debug' => true,
]);

$time=date("Y-m-d\TH:i:s.00");
$gtime=gmdate("Y-m-d\TH:i:s.00");

$response = $client->request('POST', '/modern/proxy/weight-service/user-weight', [
    'json' =>[
    'value' => $weight,
    'unitKey' => 'kg',
    'dateTimestamp' => $time,
    'gmtTimestamp' => $gtime
    ],
    'headers' => [
        'User-Agent'      => $agent,
        'origin' => 'https://connect.garmin.com',
        'referer'     => 'https://connect.garmin.com/modern/weight/',
//        'X-HTTP-Method-Override' => 'PUT',
        'authority' => 'connect.garmin.com',
        'sec-ch-ua' => '"Chromium";v="92", " Not A;Brand";v="99", "Google Chrome";v="92"',
        'dnt' => '1',
        'sec-ch-ua-mobile'=> '?0',
        'nk' => 'NT',
//        'x-app-ver' => '4.45.1.2',
        'sec-fetch-site' => 'same-origin',
        'sec-fetch-mode' => 'cors',
        'sec-fetch-dest' => 'empty',
        'accept-language' => 'en-US,en;q=0.9',
        
    ],
//    'debug' => true,
]);


echo $response->getStatusCode();



?>
