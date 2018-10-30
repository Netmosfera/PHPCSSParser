<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Tokenizer\CheckedTokenizer;

function getTokens(String $css){
    $tokenizer = new CheckedTokenizer();
    return $tokenizer->tokenize($css);
}
