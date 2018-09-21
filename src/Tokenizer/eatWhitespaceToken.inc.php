<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Consumes a {@see WhitespaceToken}, if any.
 */
function eatWhitespaceToken(
    Traverser $traverser,
    String $whitespaceRegExpSet
): ?WhitespaceToken{

    $whitespaces = $traverser->eatExp('[' . $whitespaceRegExpSet . ']+');

    return $whitespaces === NULL ? NULL : new WhitespaceToken($whitespaces);
}
