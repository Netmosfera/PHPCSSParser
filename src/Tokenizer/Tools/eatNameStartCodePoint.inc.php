<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatNameStartCodePoint(Traverser $t): ?String{
    return $t->eatStr("_") ?? eatLetter($t) ?? eatNonASCIICodePoint($t);
}
