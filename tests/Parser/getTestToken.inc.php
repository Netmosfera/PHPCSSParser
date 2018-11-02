<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Error;

function getTestToken(String $CSS){
    $tokens = getTestTokens($CSS);
    if(count($tokens) !== 1){
        throw new Error();
    }
    return $tokens[0];
}
