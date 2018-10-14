<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use Closure;

function eatURLTokenFunction(?URLToken $URL): Closure{
    return function(Traverser $traverser) use($URL): ?URLToken{
        if($URL === NULL){
            return NULL;
        }else{
            $stringValue = $URL->whitespaceBefore();
            $stringValue .= implode("", $URL->pieces());
            $stringValue .= $URL->whitespaceAfter() . ")";
            return $traverser->eatString($stringValue) === NULL ? NULL : $URL;
        }
    };
}
