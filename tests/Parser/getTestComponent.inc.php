<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Error;

function getTestComponent(String $CSS){
    $components = getTestComponents($CSS);
    if(count($components) !== 1){
        throw new Error();
    }
    return $components[0];
}
