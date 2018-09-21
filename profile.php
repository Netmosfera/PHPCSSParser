<?php

use Netmosfera\PHPCSSAST\Tokenizer\StandardTokenizer;

require(__DIR__ . "/vendor/autoload.php");

$css = file_get_contents(__DIR__ . "/1.css");

gc_disable();

echo "jit: " . ini_get("pcre.jit") . "\n";

$tokenizer = new StandardTokenizer(FALSE);
$s = microtime(TRUE);
$result = $tokenizer->tokenize($css);
echo number_format(microtime(TRUE) - $s, 10) . "\n";
file_put_contents(__DIR__ . "/tokens_ASCII.txt", print_r($result, TRUE));
