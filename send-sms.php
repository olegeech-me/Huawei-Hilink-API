#!/usr/bin/env php
<?php

// This script is meant for cli usage
//
// First argument to the script - Phone numbers (one or comma-separated list)
// Message should be suppied on STDIN

require_once 'vendor/autoload.php';

// The router class is the main entry point for interaction.
$router = new if0xx\HuaweiHilinkApi\Router;

// if specified without http or https, assumes http://
$router->setAddress('192.168.8.1');

// Username and password.
if (! $router->login('admin', 'admin')) {
  fwrite(STDERR, "Login failed\n");
  exit(1);
}


// Get number as first argument, message on STDIN
$phones = explode(',', $argv[1]);
$message = file_get_contents("php://stdin");
$sendFailed = null;

foreach ($phones as $receiver) {
  if ($router->sendSms($receiver, $message) ) {
    echo "SMS OK: $receiver\n";
  } else {
    $sendFailed = True;
    fwrite(STDERR, "SMS ERROR: $receiver\n");
  }
}

$code = isset($sendFailed) ? 1 : 0;
exit($code);

?>
