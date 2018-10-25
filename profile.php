<?php

use Netmosfera\PHPCSSAST\Tokenizer\FastTokenizer;

require(__DIR__ . "/vendor/autoload.php");

$css = file_get_contents(__DIR__ . "/tmp/test.css");
$tokenizer = new FastTokenizer();

$st = microtime(TRUE);
$tokens = $tokenizer->tokenize($css)->tokens();
echo number_format(microtime(TRUE) - $st, 10) . "\n";

$rebuild = implode("", $tokens);
file_put_contents(__DIR__ . "/tmp/test_rebuilt.css", $rebuild);
