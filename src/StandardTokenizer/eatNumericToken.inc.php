<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumericToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\DimensionToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\PercentageToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

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
        return new PercentageToken($number);
    }

    $identifier = $eatIdentifierTokenFunction($traverser);
    if($identifier !== NULL){
        return new DimensionToken($number, $identifier);
    }

    return $number;
}
