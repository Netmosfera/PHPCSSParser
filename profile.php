<?php

use Netmosfera\PHPCSSAST\Tokenizer\StandardTokenizer;

require(__DIR__ . "/vendor/autoload.php");

$css = file_get_contents(__DIR__ . "/1.css");

$tokenizer = new StandardTokenizer();
$s = microtime(TRUE);
$result = $tokenizer->tokenize($css);
echo number_format(microtime(TRUE) - $s, 10) . "\n";
file_put_contents(__DIR__ . "/tokens.txt", print_r($result, TRUE));


$tokenizer = new StandardTokenizer(FALSE);
$s = microtime(TRUE);
$result = $tokenizer->tokenize($css);
echo number_format(microtime(TRUE) - $s, 10) . "\n";
file_put_contents(__DIR__ . "/tokens.txt", print_r($result, TRUE));
