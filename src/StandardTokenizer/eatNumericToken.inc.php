<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumericToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedDimensionToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedPercentageToken;

function eatNumericToken(
    Traverser $traverser,
    Closure $eatNumberTokenFunction,
    Closure $eatIdentifierTokenFunction
): ?NumericToken{

    $number = $eatNumberTokenFunction($traverser);

    if($number === NULL){
        return NULL;
    }

    if($traverser->eatStr("%") !== NULL){
        return new CheckedPercentageToken($number);
    }

    $identifier = $eatIdentifierTokenFunction($traverser);
    if($identifier !== NULL){
        return new CheckedDimensionToken($number, $identifier);
    }

    return $number;
}
