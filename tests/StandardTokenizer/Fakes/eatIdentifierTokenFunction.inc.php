<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Closure;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;

function eatIdentifierTokenFunction(?IdentifierToken $identifier): Closure{
    return function(Traverser $traverser) use($identifier): ?IdentifierToken{
        if($identifier === NULL){
            return NULL;
        }else{
            $stringValue = (String)$identifier;
            return $traverser->eatStr($stringValue) === NULL ? NULL : $identifier;
        }
    };
}
