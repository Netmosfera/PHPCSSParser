<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Fakes;

use Closure;
use Netmosfera\PHPCSSAST\Tokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;

function eatValidEscapeTokenFunction(array $escapes): Closure{
    return function(Traverser $traverser) use($escapes): ?ValidEscapeToken{
        foreach($escapes as $escape){
            if($escape instanceof ValidEscapeToken){
                $stringValue = (String)$escape;
                if($traverser->eatString($stringValue) !== NULL){
                    return $escape;
                }
            }
        }
        return NULL;
    };
}
