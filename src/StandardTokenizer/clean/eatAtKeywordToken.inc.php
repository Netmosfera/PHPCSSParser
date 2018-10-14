<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedAtKeywordToken;

function eatAtKeywordToken(
    Traverser $traverser,
    Closure $eatIdentifierToken
): ?AtKeywordToken{
    $atKeywordTraverser = $traverser->createBranch();
    if($atKeywordTraverser->eatString("@") !== NULL){
        $identifier = $eatIdentifierToken($atKeywordTraverser);
        if(isset($identifier)){
            $traverser->importBranch($atKeywordTraverser);
            return new CheckedAtKeywordToken($identifier);
        }
    }
    return NULL;
}
