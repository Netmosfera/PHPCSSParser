<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;

function eatNameToken(
    Traverser $traverser,
    String $nameRegExpSet,
    Closure $eatEscapeFunction
): ?CheckedNameToken{

    $nameStart = $traverser->eatExp('[' . $nameRegExpSet . ']+');

    if($nameStart !== NULL){
        $pieces = [new CheckedNameBitToken($nameStart)];
    }else{
        $escape = $eatEscapeFunction($traverser);

        if($escape === NULL){
            return NULL;
        }

        $pieces = [$escape];
    }

    while(TRUE){
        $piece =
            $traverser->eatExp('[' . $nameRegExpSet . ']+') ??
            $eatEscapeFunction($traverser);

        if($piece === NULL){
            return new CheckedNameToken($pieces);
        }

        if(is_string($piece)){
            $piece = new CheckedNameBitToken($piece);
        }

        $pieces[] = $piece;
    }
}
