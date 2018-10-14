<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;

function eatWhitespaceToken(
    Traverser $traverser,
    String $whitespaceRegexSet
): ?CheckedWhitespaceToken{
    $whitespaces = $traverser->eatPattern('[' . $whitespaceRegexSet . ']+');
    if(isset($whitespaces)){
        return new CheckedWhitespaceToken($whitespaces);
    }
    return NULL;
}
