<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

function eatWhitespaceToken(
    Traverser $traverser,
    String $whitespaceRegexSet
): ?WhitespaceToken{
    $whitespaces = $traverser->eatPattern('[' . $whitespaceRegexSet . ']+');
    if(isset($whitespaces)){
        return new WhitespaceToken($whitespaces);
    }
    return NULL;
}
