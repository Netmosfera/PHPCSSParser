<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedAtKeywordToken;

function eatAtKeywordToken(
    Traverser $traverser,
    Closure $eatIdentifier
): ?AtKeywordToken{

    $atKeywordTraverser = $traverser->createBranch();

    if($atKeywordTraverser->eatStr("@") !== NULL){
        $identifier = $eatIdentifier($atKeywordTraverser);
        if($identifier !== NULL){
            $traverser->importBranch($atKeywordTraverser);
            return new CheckedAtKeywordToken($identifier);
        }
    }

    return NULL;
}
