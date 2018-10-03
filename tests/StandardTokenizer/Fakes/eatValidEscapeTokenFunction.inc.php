<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Closure;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;

function eatValidEscapeTokenFunction(Array $escapes): Closure{
    return function(Traverser $traverser) use($escapes): ?ValidEscapeToken{
        foreach($escapes as $escape){
            assert($escape instanceof ValidEscapeToken);
            $stringValue = (String)$escape;
            if($traverser->eatStr($stringValue) !== NULL){
                return $escape;
            }
        }
        return NULL;
    };
}