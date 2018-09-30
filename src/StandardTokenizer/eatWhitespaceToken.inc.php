<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;

function eatWhitespaceToken(
    Traverser $traverser,
    String $whitespaceRegExpSet
): ?CheckedWhitespaceToken{

    $whitespaces = $traverser->eatExp('[' . $whitespaceRegExpSet . ']+');

    return $whitespaces === NULL ? NULL :
        new CheckedWhitespaceToken($whitespaces);
}
