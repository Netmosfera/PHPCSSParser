<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;

function eatAtKeywordToken(
    Traverser $traverser,
    ?Closure $eatIdentifierToken = NULL,
    String $AtKeywordTokenClass = AtKeywordToken::CLASS
): ?AtKeywordToken{

    if(isset($eatIdentifierToken));else{
        $eatIdentifierToken = __NAMESPACE__ . "\\eatIdentifierToken";
    }

    $atKeywordTraverser = $traverser->createBranch();
    if($atKeywordTraverser->eatString("@") !== NULL){
        $identifier = $eatIdentifierToken($atKeywordTraverser);
        if(isset($identifier)){
            $traverser->importBranch($atKeywordTraverser);
            return new $AtKeywordTokenClass($identifier);
        }
    }

    return NULL;
}
