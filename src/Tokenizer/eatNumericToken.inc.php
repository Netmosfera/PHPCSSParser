<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumericToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\DimensionToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\PercentageToken;

function eatNumericToken(
    Traverser $traverser,
    ?Closure $eatNumberToken = NULL,
    ?Closure $eatIdentifierToken = NULL,
    String $PercentageTokenClass = PercentageToken::CLASS,
    String $DimensionTokenClass = DimensionToken::CLASS
): ?NumericToken{
    if(isset($eatNumberToken));else{
        $eatNumberToken = __NAMESPACE__ . "\\eatNumberToken";
    }
    if(isset($eatIdentifierToken));else{
        $eatIdentifierToken = __NAMESPACE__ . "\\eatIdentifierToken";
    }

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
