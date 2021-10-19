<?php

namespace Examples;

require __DIR__ . '/../lib/subdomainAPI.php';
require __DIR__ .'/../vendor/autoload.php';
use lib\subdomainAPI as api;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$data = ([
    'API_Token' => '',  // You have to enter the API Key here
    'sld' => 'coolnewsubdomain',
    'tld' => '.look.in',
    'tldsToInclude' => array(
        '.co.de',
        '.look.in'
    ),
    'regperiod' => 1,
    'registrant' => array(
        'firstname' => 'John',
        'lastname' => 'Doe',
        'companyname' => 'Awesome Inc',
        'email' => 'yours@mail.de',
        'address1' => 'Some Road 1',
        'address2' => '4th Floor',
        'city' => 'Berlin',
        'state' => 'BE',
        'postcode' => '12345',
        'countrycode' => 'DE',
        'phone' => '+49.15478632158'
    ),
    'admin' => array(
        'firstname' => 'John',
        'lastname' => 'Doe',
        'companyname' => 'Awesome Inc',
        'email' => 'yours@mail.de',
        'address1' => 'Some Road 1',
        'address2' => '4th Floor',
        'city' => 'Berlin',
        'state' => 'BE',
        'postcode' => '12345',
        'countrycode' => 'DE',
        'phone' => '+49.15478632158'
    ),
    'tech' => array(
        'firstname' => 'John',
        'lastname' => 'Doe',
        'companyname' => 'Awesome Inc',
        'email' => 'yours@mail.de',
        'address1' => 'Some Road 1',
        'address2' => '4th Floor',
        'city' => 'Berlin',
        'state' => 'BE',
        'postcode' => '12345',
        'countrycode' => 'DE',
        'phone' => '+49.15478632158'
    ),
    'billing' => array(
        'firstname' => 'John',
        'lastname' => 'Doe',
        'companyname' => 'Awesome Inc',
        'email' => 'yours@mail.de',
        'address1' => 'Some Road 1',
        'address2' => '4th Floor',
        'city' => 'Berlin',
        'state' => 'BE',
        'postcode' => '12345',
        'countrycode' => 'DE',
        'phone' => '+49.15478632158'
    ),
    'nameservers' => array(
    'ns1' => 'your1.awesome.ns',
    'ns2' => 'your2.awesome.ns',
),

]);

$logger = new Logger('INFO');
$logger->pushHandler(new StreamHandler(__DIR__.'/../logs/info.log', Logger::DEBUG));

$api = new api;

$result = $api->RegisterDomain($data);
$logger->info(json_encode($result));
$result = $api->RenewDomain($data);
$logger->info(json_encode($result));
$result = $api->GetNameservers($data);
$logger->info(json_encode($result));
$result = $api->SaveNameservers($data);
$logger->info(json_encode($result));
$result = $api->GetContactDetails($data);
$logger->info(json_encode($result));
$result = $api->SaveContactDetails($data);
$logger->info(json_encode($result));
$result = $api->CheckAvailability($data);
$logger->info(json_encode($result));
$result = $api->RequestDelete($data);
$logger->info(json_encode($result));
$result = $api->Sync($data);
$logger->info(json_encode($result));
$result = $api->GetEPPCode($data);
$logger->info(json_encode($result));


#$logger->info(implode('|',$result));


