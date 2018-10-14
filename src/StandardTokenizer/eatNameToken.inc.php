<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;

function eatNameToken(
    Traverser $traverser,
    String $nameRegexSet,
    Closure $eatEscapeToken
): ?CheckedNameToken{

    $nameStart = $traverser->eatPattern('[' . $nameRegexSet . ']+');

    if(isset($nameStart)){
        $pieces = [new CheckedNameBitToken($nameStart)];
    }else{
        $escape = $eatEscapeToken($traverser);
        if($escape === NULL){
            return NULL;
        }
        $pieces = [$escape];
    }

    while(TRUE){
        $bit = $traverser->eatPattern('[' . $nameRegexSet . ']+');
        if(isset($bit)){
            $piece = new CheckedNameBitToken($bit);
        }else{
            $piece = $eatEscapeToken($traverser);
        }
        if($piece === NULL){
            return new CheckedNameToken($pieces);
        }
        $pieces[] = $piece;
    }
}
