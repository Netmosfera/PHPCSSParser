<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierLikeToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Consumes a {@see IdentifierLikeToken}, if any.
 */
function eatIdentifierLikeToken(
    Traverser $traverser,
    Closure $eatIdentifierToken,
    String $eatWhitespaceRegexSet,
    Closure $eatURLToken
): ?IdentifierLikeToken{

    $identifier = $eatIdentifierToken($traverser);

    if($identifier === NULL){
        return NULL;
    }

    if($traverser->eatStr("(") === NULL){
        return $identifier;
    }

    /** @var IdentifierToken $identifier */

    if($identifier->getName()->getValue() === "url"){
        $URLToken = $eatURLToken($traverser);
        if($URLToken !== NULL){
            return $URLToken;
        }
    }

    return new FunctionToken($identifier);
}
