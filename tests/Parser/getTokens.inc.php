<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Tokenizer\Tokenizer;

function getTokens(String $css){
    $tokenizer = new Tokenizer();
    return $tokenizer->tokenize($css);
}
