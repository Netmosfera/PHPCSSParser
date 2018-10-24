<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\HashToken;

function eatHashToken(
    Traverser $traverser,
    ?Closure $eatNameToken = NULL,
    String $HashTokenClass = HashToken::CLASS
): ?HashToken{
    if(isset($eatNameToken));else{
        $eatNameToken = __NAMESPACE__ . "\\eatNameToken";
    }
    $hashTraverser = $traverser->createBranch();
    if($hashTraverser->eatString("#") !== NULL){
        $name = $eatNameToken($hashTraverser);
        if(isset($name)){
            $traverser->importBranch($hashTraverser);
            return new $HashTokenClass($name);
        }
    }
    return NULL;
}
