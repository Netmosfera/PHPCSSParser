<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\has;
use Netmosfera\PHPCSSAST\Tokens\Whitespaces;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatWhitespaces(Traverser $t): ?Whitespaces{
    $whitespaces = $t->eatExp('[\r\n\f\t ]+');
    return has($whitespaces) ? new Whitespaces($whitespaces) : NULL;
}
