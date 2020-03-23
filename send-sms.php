#!/usr/bin/env php
<?php
require_once 'vendor/autoload.php';

// The router class is the main entry point for interaction.
$router = new if0xx\HuaweiHilinkApi\Router;

// if specified without http or https, assumes http://
$router->setAddress('192.168.8.1');

// Username and password.
// Username is always admin as far as I can tell, default password is admin as well.
$router->login('admin', 'admin');

// Get number as first argument, message on STDIN
$receiver = $argv[1];
$message = file_get_contents("php://stdin");

if ($router->sendSms($receiver, $message) ) {
	echo "SMS SENT OK\n";
	exit(0);
} else {
	echo "SMS ERROR\n";
	exit(1);
}
?>
