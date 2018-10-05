<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked;

use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;

function piecesIntendedValue(Iterable $pieces){
    $intendedValue = "";
    foreach($pieces as $piece){
        assert($piece instanceof EvaluableToken);
        $intendedValue .= $piece->intendedValue();
    }
    return $intendedValue;
}
