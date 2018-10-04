<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked;

use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;
use TypeError;

function piecesIntendedValue(Iterable $pieces){
    $intendedValue = "";
    foreach($pieces as $piece){
        if($piece instanceof EvaluableToken === FALSE){
            throw new TypeError();
        }
        /** @var EvaluableToken $piece */
        $intendedValue .= $piece->intendedValue();
    }
    return $intendedValue;
}
