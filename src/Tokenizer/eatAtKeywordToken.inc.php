<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedAtKeywordToken;

function eatAtKeywordToken(
    Traverser $traverser,
    Closure $eatIdentifierToken,
    String $AtKeywordTokenClass = CheckedAtKeywordToken::CLASS
): ?AtKeywordToken{
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
