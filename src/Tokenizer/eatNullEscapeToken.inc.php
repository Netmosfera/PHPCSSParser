<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\NullEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscapeToken;

function eatNullEscapeToken(
    Traverser $traverser,
    String $newlineRegex = SpecData::NEWLINES_REGEX_SEQS,
    String $EOFEscapeTokenClass = EOFEscapeToken::CLASS,
    String $ContinuationEscapeTokenClass = ContinuationEscapeToken::CLASS
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
