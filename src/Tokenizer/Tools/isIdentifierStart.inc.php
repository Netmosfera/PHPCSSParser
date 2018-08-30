<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\has;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\Escapes\isBackslashAndValidEscape;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function isIdentifierStart(Traverser $t): Bool{
    $t = $t->createBranch();
    if(has($t->eatStr("-"))){
        return has($t->eatStr("-")) || has(eatNameStartCodePoint($t)) || isBackslashAndValidEscape($t);
    }
    return eatNameStartCodePoint($t) !== NULL || isBackslashAndValidEscape($t);
}
