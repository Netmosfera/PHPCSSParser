<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierLikeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedFunctionToken;

function eatIdentifierLikeToken(
    Traverser $traverser,
    Closure $eatIdentifierToken,
    Closure $eatURLToken,
    String $FunctionTokenClass = CheckedFunctionToken::CLASS
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
    return new $FunctionTokenClass($identifier);
}
