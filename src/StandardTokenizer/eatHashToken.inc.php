<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Names\HashToken;
use Closure;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatHashToken(
    Traverser $traverser,
    Closure $eatNameToken
): ?HashToken{

    $hashTraverser = $traverser->createBranch();

    if($hashTraverser->eatStr("#") !== NULL){
        $name = $eatNameToken($hashTraverser);
        if($name !== NULL){
            $traverser->importBranch($hashTraverser);
            return new HashToken($name);
        }
    }

    return NULL;
}