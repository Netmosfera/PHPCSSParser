<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Error;
use Netmosfera\PHPCSSAST\Tokenizer\StandardTokenizer;

function getToken(String $css){
    $tokenizer = new StandardTokenizer();
    $tokens = $tokenizer->tokenize($css);
    if(count($tokens) !== 1){
        throw new Error();
    }
    return $tokens[0];
}
