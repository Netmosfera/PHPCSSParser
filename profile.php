<?php

use Netmosfera\PHPCSSAST\StandardTokenizer\StandardTokenizer;

require(__DIR__ . "/vendor/autoload.php");

$css = file_get_contents(__DIR__ . "/tmp/test.css");

$tokenizer = new StandardTokenizer(FALSE);
$s = microtime(TRUE);
$tokens = $tokenizer->tokenize($css);
echo number_format(microtime(TRUE) - $s, 10) . "\n";

$rebuild = "";
foreach($tokens as $token){
    $rebuild .= (String)$token;
}
file_put_contents(__DIR__ . "/tmp/test_rebuilt.css", $rebuild);
