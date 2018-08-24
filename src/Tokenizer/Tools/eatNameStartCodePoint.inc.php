<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\has;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatNameStartCodePoint(Traverser $t): ?String{
    if(has($c = $t->eatStr("_"))){
        return $c;
    }

    if(has($c = eatLetter($t))){
        return $c;
    }

    if(has($c = eatNonASCIICodePoint($t))){
        return $c;
    }

    return NULL;
}
