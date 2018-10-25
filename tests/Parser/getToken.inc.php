<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Error;
use Netmosfera\PHPCSSAST\Tokens\Tokens;
use Netmosfera\PHPCSSAST\Tokenizer\StandardTokenizer;

function getToken(String $css){
    $tokenizer = new StandardTokenizer(function(array $tokens): Tokens{
        return new Tokens($tokens);
    });
    $tokens = $tokenizer->tokenize($css)->tokens();
    if(count($tokens) !== 1){
        throw new Error();
    }
    return $tokens[0];
}
