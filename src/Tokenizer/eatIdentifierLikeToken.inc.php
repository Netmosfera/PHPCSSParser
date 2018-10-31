<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierLikeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;

function eatIdentifierLikeToken(
    Traverser $traverser,
    Closure $eatIdentifierToken,
    Closure $eatURLToken
): ?IdentifierLikeToken{
    $identifier = $eatIdentifierToken($traverser);
    if(isset($identifier));else{
        return NULL;
    }
    if($traverser->eatString("(") === NULL){
        return $identifier;
    }
    /** @var IdentifierToken $identifier */
    if(strtolower($identifier->name()->intendedValue()) === "url"){
        $URLToken = $eatURLToken($traverser, $identifier);
        if(isset($URLToken)){
            return $URLToken;
        }
    }
    return new FunctionToken($identifier);
}
