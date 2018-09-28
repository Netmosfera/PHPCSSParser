<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\HashToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedHashToken;

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
            return new CheckedHashToken($name);
        }
    }

    return NULL;
}
