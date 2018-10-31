<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;

function eatNameToken(
    Traverser $traverser,
    String $nameRegexSet,
    Closure $eatEscapeToken
): ?NameToken{

    $nameStart = $traverser->eatPattern('[' . $nameRegexSet . ']+');

    if(isset($nameStart)){
        $pieces = [new NameBitToken($nameStart)];
    }else{
        $escape = $eatEscapeToken($traverser);
        if(isset($escape));else{
            return NULL;
        }
        $pieces = [$escape];
    }

    while(TRUE){
        $bit = $traverser->eatPattern('[' . $nameRegexSet . ']+');
        if(isset($bit)){
            $piece = new NameBitToken($bit);
        }else{
            $piece = $eatEscapeToken($traverser);
        }
        if(isset($piece));else{
            return new NameToken($pieces);
        }
        $pieces[] = $piece;
    }
}
