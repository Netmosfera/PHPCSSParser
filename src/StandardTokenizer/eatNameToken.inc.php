<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;

function eatNameToken(
    Traverser $traverser,
    String $nameRegexSet,
    Closure $eatEscapeToken,
    String $NameBitTokenClass = CheckedNameBitToken::CLASS,
    String $NameTokenClass = CheckedNameToken::CLASS
): ?NameToken{

    $nameStart = $traverser->eatPattern('[' . $nameRegexSet . ']+');

    if(isset($nameStart)){
        $pieces = [new $NameBitTokenClass($nameStart)];
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
            $piece = new $NameBitTokenClass($bit);
        }else{
            $piece = $eatEscapeToken($traverser);
        }
        if($piece === NULL){
            return new $NameTokenClass($pieces);
        }
        $pieces[] = $piece;
    }
}
