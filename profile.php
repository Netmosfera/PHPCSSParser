<?php

use Netmosfera\PHPCSSAST\Tokenizer\StandardTokenizer;

require(__DIR__ . "/vendor/autoload.php");

$css = file_get_contents(__DIR__ . "/1.css");

$tokenizer = new StandardTokenizer(FALSE);
$s = microtime(TRUE);
$tokens = $tokenizer->tokenize($css);
echo number_format(microtime(TRUE) - $s, 10) . "\n";
file_put_contents(__DIR__ . "/tokens_ASCII.txt", print_r($tokens, TRUE));


$rebuild = "";
foreach($tokens as $token){
    $rebuild .= (String)$token;
}
file_put_contents(__DIR__ . "/1_rebuilt.css", $rebuild);
