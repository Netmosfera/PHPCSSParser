<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;

function eatNumberTokenFunction(?NumberToken $number): Closure{
    return function(Traverser $traverser) use($number): ?NumberToken{
        if($number === NULL){
            return NULL;
        }else{
            $stringValue = (String)$number;
            return $traverser->eatString($stringValue) === NULL ? NULL : $number;
        }
    };
}
