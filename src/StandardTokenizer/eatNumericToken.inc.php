<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumericToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedDimensionToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedPercentageToken;

function eatNumericToken(
    Traverser $traverser,
    Closure $eatNumberToken,
    Closure $eatIdentifierToken,
    String $PercentageTokenClass = CheckedPercentageToken::CLASS,
    String $DimensionTokenClass = CheckedDimensionToken::CLASS
): ?NumericToken{

    $number = $eatNumberToken($traverser);

    if(isset($number));else{
        return NULL;
    }

    if($traverser->eatString("%") !== NULL){
        return new $PercentageTokenClass($number);
    }

    $identifier = $eatIdentifierToken($traverser);
    if(isset($identifier)){
        return new $DimensionTokenClass($number, $identifier);
    }

    return $number;
}
