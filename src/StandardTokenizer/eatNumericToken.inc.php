<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumericToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedDimensionToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedPercentageToken;

function eatNumericToken(
    Traverser $traverser,
    Closure $eatNumberToken,
    Closure $eatIdentifierToken
): ?NumericToken{

    $number = $eatNumberToken($traverser);

    if($number === NULL){
        return NULL;
    }

    if($traverser->eatString("%") !== NULL){
        return new CheckedPercentageToken($number);
    }

    $identifier = $eatIdentifierToken($traverser);
    if($identifier !== NULL){
        return new CheckedDimensionToken($number, $identifier);
    }

    return $number;
}
