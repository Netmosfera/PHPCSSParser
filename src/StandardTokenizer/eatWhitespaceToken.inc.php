<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;

function eatWhitespaceToken(
    Traverser $traverser,
    String $whitespaceRegexSet,
    String $WhitespaceTokenClass = CheckedWhitespaceToken::CLASS
): ?WhitespaceToken{
    $whitespaces = $traverser->eatPattern('[' . $whitespaceRegexSet . ']+');
    if(isset($whitespaces)){
        return new $WhitespaceTokenClass($whitespaces);
    }
    return NULL;
}
