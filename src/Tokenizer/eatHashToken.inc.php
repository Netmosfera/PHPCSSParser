<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\HashToken;

function eatHashToken(Traverser $traverser, Closure $eatNameToken): ?HashToken{
    $hashTraverser = $traverser->createBranch();
    if($hashTraverser->eatString("#") !== NULL){
        $name = $eatNameToken($hashTraverser);
        if(isset($name)){
            $traverser->importBranch($hashTraverser);
            return new HashToken($name);
        }
    }
    return NULL;
}
