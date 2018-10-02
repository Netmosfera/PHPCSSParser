<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;

function eatNameTokenFunction(?NameToken $nameToken): Closure{
    return function(Traverser $traverser) use($nameToken): ?NameToken{
        if($nameToken === NULL){
            return NULL;
        }else{
            $stringValue = (String)$nameToken;
            return $traverser->eatStr($stringValue) === NULL ? NULL : $nameToken;
        }
    };
}
