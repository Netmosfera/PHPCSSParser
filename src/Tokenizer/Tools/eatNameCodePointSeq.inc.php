<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\has;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatNameCodePointSeq(Traverser $t): ?String{
    // @TODO this is slow as hell, should use regexps
    if(has($cp = eatNameCodePoint($t))){
        return $cp . eatNameCodePointSeq($t);
    }
    return NULL;
}
