#!/usr/bin/env php
<?php

// This script is meant for cli usage
//
// First argument to the script - which messages are we clearing,
// either 'inbox' or 'sent'

$numberSmsToList = 50;

require_once 'vendor/autoload.php';

// The router class is the main entry point for interaction.
$router = new if0xx\HuaweiHilinkApi\Router;

// if specified without http or https, assumes http://
$router->setAddress('192.168.8.1');

// Username and password.
if (! $router->login('admin', 'admin')) {
  echo "Login failed\n";
  exit(1);
}

# what are we clearing?
if ($argv[1] == 'inbox') {
  $data = $router->getInBox(1, $numberSmsToList, false);
} elseif ($argv[1] == 'sent') {
  $data = $router->getSentBox(1, $numberSmsToList, false);
} else {
  echo "Please, use 'inbox' or 'sent' as the first argument to the script\n";
  exit(1);
}

# exit if no messages
if (! $data->Messages->Message) {
  echo "No messages in '$argv[1]' to delete\n";
  exit(1);
}
$total = count($data->Messages->Message);

for ($i = 0; $i < $total ; $i++) {
  $index = $data->Messages->Message[$i]->Index;
  $router->deleteSms($index);
  echo "SMS [$index] was deleted\n";
}

echo "\nAll messages in '$argv[1]' were deleted successfully\n";

