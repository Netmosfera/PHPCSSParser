<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Netmosfera\PHPCSSAST\Tokens\Escapes\NullEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;

function eatNullEscapeToken(
    Traverser $traverser,
    String $newlineRegex,
    String $EOFEscapeTokenClass = CheckedEOFEscapeToken::CLASS,
    String $ContinuationEscapeTokenClass = CheckedContinuationEscapeToken::CLASS
): ?NullEscapeToken{

    $beforeBackslash = $traverser->index;

    if($traverser->eatString("\\") === NULL){
        return NULL;
    }

    if(isset($traverser->data[$traverser->index]));else{
        return new $EOFEscapeTokenClass();
    }

    $newline = $traverser->eatPattern($newlineRegex);
    if(isset($newline)){
        return new $ContinuationEscapeTokenClass($newline);
    }

    $traverser->index = $beforeBackslash;
    return NULL;
}
