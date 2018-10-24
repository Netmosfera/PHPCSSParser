<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;

function eatEscapeToken(
    Traverser $traverser,
    ?Closure $eatValidEscapeToken = NULL,
    ?Closure $eatNullEscapeToken = NULL
): ?EscapeToken{
    if(isset($eatValidEscapeToken));else{
        $eatValidEscapeToken = __NAMESPACE__ . "\\eatValidEscapeToken";
    }
    if(isset($eatNullEscapeToken));else{
        $eatNullEscapeToken = __NAMESPACE__ . "\\eatNullEscapeToken";
    }

    $escape = $eatValidEscapeToken($traverser);
    if(isset($escape)){
        return $escape;
    }

    $escape = $eatNullEscapeToken($traverser);
    if(isset($escape)){
        return $escape;
    }

    return NULL;
}
