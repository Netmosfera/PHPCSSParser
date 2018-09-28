<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Closure;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatAtKeywordToken(
    Traverser $traverser,
    Closure $eatIdentifier
): ?AtKeywordToken{

    $atKeywordTraverser = $traverser->createBranch();

    if($atKeywordTraverser->eatStr("@") !== NULL){
        $identifier = $eatIdentifier($atKeywordTraverser);
        if($identifier !== NULL){
            $traverser->importBranch($atKeywordTraverser);
            return new AtKeywordToken($identifier);
        }
    }

    return NULL;
}
