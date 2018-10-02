<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;

function eatEscapeTokenFunction(Array $escapeTokens): Closure{
    return function(Traverser $traverser) use($escapeTokens): ?EscapeToken{
        foreach($escapeTokens as $escapeToken){
            assert($escapeToken instanceof EscapeToken);
            $stringValue = (String)$escapeToken;
            if($traverser->eatStr($stringValue) !== NULL){
                return $escapeToken;
            }
        }
        return NULL;
    };
}
