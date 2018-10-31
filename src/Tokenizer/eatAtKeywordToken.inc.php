<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;

function eatAtKeywordToken(
    Traverser $traverser,
    Closure $eatIdentifierToken
): ?AtKeywordToken{
    $atKeywordTraverser = $traverser->createBranch();
    if($atKeywordTraverser->eatString("@") !== NULL){
        $identifier = $eatIdentifierToken($atKeywordTraverser);
        if(isset($identifier)){
            $traverser->importBranch($atKeywordTraverser);
            return new AtKeywordToken($identifier);
        }
    }
    return NULL;
}
