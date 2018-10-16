<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Fakes;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokenizer\Traverser;

function eatEscapeTokenFunction(array $escapes): Closure{
    return function(Traverser $traverser) use($escapes): ?EscapeToken{
        foreach($escapes as $escape){
            if($escape instanceof EscapeToken){
                $stringValue = (String)$escape;
                if($traverser->eatString($stringValue) !== NULL){
                    return $escape;
                }
            }
        }
        return NULL;
    };
}
