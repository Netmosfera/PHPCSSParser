<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierLikeToken;

function eatIdentifierLikeToken(
    Traverser $traverser,
    ?Closure $eatIdentifierToken = NULL,
    ?Closure $eatURLToken = NULL,
    String $FunctionTokenClass = FunctionToken::CLASS
): ?IdentifierLikeToken{
    if(isset($eatIdentifierToken));else{
        $eatIdentifierToken = __NAMESPACE__ . "\\eatIdentifierToken";
    }
    if(isset($eatURLToken));else{
        $eatURLToken = __NAMESPACE__ . "\\eatURLToken";
    }
    $identifier = $eatIdentifierToken($traverser);
    if(isset($identifier));else{
        return NULL;
    }
    if($traverser->eatString("(") === NULL){
        return $identifier;
    }
    /** @var IdentifierToken $identifier */
    if($identifier->name()->intendedValue() === "url"){
        $URLToken = $eatURLToken($traverser, $identifier);
        if(isset($URLToken)){
            return $URLToken;
        }
    }
    return new $FunctionTokenClass($identifier);
}
