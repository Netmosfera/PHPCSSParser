<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use Closure;

function eatURLTokenFunction(?URLToken $URLToken): Closure{
    return function(Traverser $traverser) use($URLToken): ?URLToken{
        if($URLToken === NULL){
            return NULL;
        }else{
            $stringValue = $URLToken->whitespaceBefore();
            $stringValue .= implode("", $URLToken->pieces());
            $stringValue .= $URLToken->whitespaceAfter() . ")";
            return $traverser->eatStr($stringValue) === NULL ? NULL : $URLToken;
        }
    };
}
