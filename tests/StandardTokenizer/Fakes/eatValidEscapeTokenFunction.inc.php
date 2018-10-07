<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Closure;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;

function eatValidEscapeTokenFunction(array $escapes): Closure{
    return function(Traverser $traverser) use($escapes): ?ValidEscapeToken{
        foreach($escapes as $escape){
            if($escape instanceof ValidEscapeToken){
                $stringValue = (String)$escape;
                if($traverser->eatStr($stringValue) !== NULL){
                    return $escape;
                }
            }
        }
        return NULL;
    };
}
