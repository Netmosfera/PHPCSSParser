<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Error;

function getToken(String $css){
    $tokens = getTokens($css);
    if(count($tokens) !== 1){
        throw new Error();
    }
    return $tokens[0];
}
