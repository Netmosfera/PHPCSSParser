<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\NullEscapeToken;

function eatNullEscapeToken(
    Traverser $traverser,
    String $newlineRegex
): ?NullEscapeToken{

    $beforeBackslash = $traverser->index;

    if($traverser->eatString("\\") === NULL){
        return NULL;
    }

    if(isset($traverser->data[$traverser->index]));else{
        return new EOFEscapeToken();
    }

    $newline = $traverser->eatPattern($newlineRegex);
    if(isset($newline)){
        return new ContinuationEscapeToken($newline);
    }

    $traverser->index = $beforeBackslash;
    return NULL;
}
