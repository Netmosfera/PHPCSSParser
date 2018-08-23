<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\has;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function isNumberStart(Traverser $t): Bool{
    $t = $t->createBranch();
    if(has($t->eatExp('\+|-'))){
        if(has(eatDigit($t))){
            return TRUE;
        }
        return has($t->eatStr(".")) && has(eatDigit($t));
    }elseif(has($t->eatStr("."))){
        return has(eatDigit($t));
    }elseif(has(eatDigit($t))){
        return TRUE;
    }
    return FALSE;
}
