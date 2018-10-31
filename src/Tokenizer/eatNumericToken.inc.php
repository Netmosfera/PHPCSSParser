<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Numbers\DimensionToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumericToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\PercentageToken;

function eatNumericToken(
    Traverser $traverser,
    Closure $eatNumberToken,
    Closure $eatIdentifierToken
): ?NumericToken{

    $number = $eatNumberToken($traverser);

    if(isset($number));else{
        return NULL;
    }

    if($traverser->eatString("%") !== NULL){
        return new PercentageToken($number);
    }

    $identifier = $eatIdentifierToken($traverser);
    if(isset($identifier)){
        return new DimensionToken($number, $identifier);
    }

    return $number;
}
