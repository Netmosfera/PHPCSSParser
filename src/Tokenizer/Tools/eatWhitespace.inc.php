<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatWhitespace(Traverser $t): ?String{
    return $t->eatExp(' |\t') ?? eatNewline($t);
}
