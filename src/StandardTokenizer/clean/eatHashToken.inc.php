<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\HashToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedHashToken;

function eatHashToken(Traverser $traverser, Closure $eatNameToken): ?HashToken{
    $hashTraverser = $traverser->createBranch();
    if($hashTraverser->eatString("#") !== NULL){
        $name = $eatNameToken($hashTraverser);
        if(isset($name)){
            $traverser->importBranch($hashTraverser);
            return new CheckedHashToken($name);
        }
    }
    return NULL;
}
