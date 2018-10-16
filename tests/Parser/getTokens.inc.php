<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Tokenizer\StandardTokenizer;

function getTokens(String $css){
    $tokenizer = new StandardTokenizer();
    return $tokenizer->tokenize($css);
}
