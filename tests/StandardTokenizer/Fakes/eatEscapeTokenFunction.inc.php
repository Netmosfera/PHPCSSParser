<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;

function eatEscapeTokenFunction(Array $escapes): Closure{
    return function(Traverser $traverser) use($escapes): ?EscapeToken{
        foreach($escapes as $escape){
            assert($escape instanceof EscapeToken);
            $stringValue = (String)$escape;
            if($traverser->eatStr($stringValue) !== NULL){
                return $escape;
            }
        }
        return NULL;
    };
}

// @TODO this, which returns EscapeToken, is used also where only ValidEscapeToken is
// expected - must be a different function in those cases
