<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\WhitespaceToken;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatWhitespaceToken(Traverser $t): ?WhitespaceToken{
    $whitespaces = $t->eatExp('[\r\n\f\t ]+');
    return has($whitespaces) ? new WhitespaceToken($whitespaces) : NULL;
}
