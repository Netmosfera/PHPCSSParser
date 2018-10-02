<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Closure;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;

function eatIdentifierTokenFunction(?IdentifierToken $identifierToken): Closure{
    return function(Traverser $traverser) use($identifierToken): ?IdentifierToken{
        if($identifierToken === NULL){
            return NULL;
        }else{
            $stringValue = (String)$identifierToken;
            return $traverser->eatStr($stringValue) === NULL ? NULL : $identifierToken;
        }
    };
}
