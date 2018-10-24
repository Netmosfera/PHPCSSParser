<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;

function eatNameToken(
    Traverser $traverser,
    String $nameRegexSet = SpecData::NAME_COMPONENTS_BYTES_REGEX_SET,
    ?Closure $eatValidEscapeToken = NULL,
    String $NameBitTokenClass = NameBitToken::CLASS,
    String $NameTokenClass = NameToken::CLASS
): ?NameToken{
    if(isset($eatValidEscapeToken));else{
        $eatValidEscapeToken = __NAMESPACE__ . "\\eatValidEscapeToken";
    }

    $nameStart = $traverser->eatPattern('[' . $nameRegexSet . ']+');

    if(isset($nameStart)){
        $pieces = [new $NameBitTokenClass($nameStart)];
    }else{
        $escape = $eatValidEscapeToken($traverser);
        if(isset($escape));else{
            return NULL;
        }
        $pieces = [$escape];
    }

    while(TRUE){
        $bit = $traverser->eatPattern('[' . $nameRegexSet . ']+');
        if(isset($bit)){
            $piece = new $NameBitTokenClass($bit);
        }else{
            $piece = $eatValidEscapeToken($traverser);
        }
        if(isset($piece));else{
            return new $NameTokenClass($pieces);
        }
        $pieces[] = $piece;
    }
}
