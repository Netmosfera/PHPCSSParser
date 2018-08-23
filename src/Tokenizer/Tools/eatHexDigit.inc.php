<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatHexDigit(Traverser $t): ?String{
    return $t->eatExp('[a-fA-F0-9]');
}
