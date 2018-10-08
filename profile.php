<?php

use Netmosfera\PHPCSSAST\StandardTokenizer\StandardTokenizer;

require(__DIR__ . "/vendor/autoload.php");

$css = file_get_contents(__DIR__ . "/tmp/test.css");

$tokenizer = new StandardTokenizer();
$tokens = $tokenizer->tokenize($css);
$rebuild = "";
foreach($tokens as $token){
    $rebuild .= (String)$token;
}

file_put_contents(__DIR__ . "/tmp/test_rebuilt.css", $rebuild);
