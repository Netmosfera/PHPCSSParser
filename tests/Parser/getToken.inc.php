<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Error;
use Netmosfera\PHPCSSAST\Tokenizer\CheckedTokenizer;

function getToken(String $css){
    $tokenizer = new CheckedTokenizer();
    $tokens = $tokenizer->tokenize($css)->tokens();
    if(count($tokens) !== 1){
        throw new Error();
    }
    return $tokens[0];
}
