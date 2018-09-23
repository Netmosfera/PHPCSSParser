<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatNameToken(
    Traverser $traverser,
    String $nameRegExpSet,
    Closure $eatEscapeFunction
): ?NameToken{

    $nameStart = $traverser->eatExp('[' . $nameRegExpSet . ']+');

    if($nameStart !== NULL){
        $pieces = [$nameStart];
    }else{
        $escape = $eatEscapeFunction($traverser);

        if($escape === NULL){
            return NULL;
        }

        $pieces = [$escape];
    }

    LOOP:

    $piece = $traverser->eatExp('[' . $nameRegExpSet . ']+') ?? $eatEscapeFunction($traverser);

    if($piece === NULL){
        return new NameToken($pieces);
    }

    $pieces[] = $piece;

    goto LOOP;
}
