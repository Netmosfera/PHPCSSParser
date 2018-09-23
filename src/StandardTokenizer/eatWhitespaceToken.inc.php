<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

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
