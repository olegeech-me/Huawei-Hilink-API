#!/usr/bin/env php
<?php

// This script is meant for cli usage
//
// First argument to the script - which messages are we listing,
// either 'inbox' or 'sent'

require_once 'vendor/autoload.php';

function sortByIndex($a, $b) {
  // sort sms by index
  $retval = strcmp($a->Index, $b->Index);
  return $retval;
}

// The router class is the main entry point for interaction.
$router = new if0xx\HuaweiHilinkApi\Router;

// if specified without http or https, assumes http://
$router->setAddress('192.168.8.1');

// Username and password.
$router->login('admin', 'admin');

# what are we listing?
if ($argv[1] == 'inbox') {
  $data = $router->getInBox();
} elseif ($argv[1] == 'sent') {
  $data = $router->getSentBox();
} else {
  echo "Please, use 'inbox' or 'sent' as the first argument to the script\n";
  exit(1);
}

# translate simpleXML to normal array, so we can sort stuff
$messages = array();
foreach ($data->Messages->Message as $obj) {
  $messages[] = $obj;
}
usort($messages, 'sortByIndex');

# print sorted messages
foreach ($messages as $message) {
  $report .= <<<EOF

Index: $message->Index
Date: $message->Date
Phone: $message->Phone
Text: $message->Content

EOF;

}

print($report);
