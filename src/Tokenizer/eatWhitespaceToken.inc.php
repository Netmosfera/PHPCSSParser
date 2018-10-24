<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

function eatWhitespaceToken(
    Traverser $traverser,
    String $whitespaceRegexSet = SpecData::WHITESPACES_REGEX_SET,
    String $WhitespaceTokenClass = WhitespaceToken::CLASS
): ?WhitespaceToken{

    $whitespaces = $traverser->eatPattern('[' . $whitespaceRegexSet . ']+');
    if(isset($whitespaces)){
        return new $WhitespaceTokenClass($whitespaces);
    }

    return NULL;
}
