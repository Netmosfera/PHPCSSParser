<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Netmosfera\PHPCSSAST\Tokens\Escapes\NullEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;

function eatNullEscapeToken(
    Traverser $traverser,
    String $newlineRegex
): ?NullEscapeToken{

    $beforeBackslash = $traverser->savepoint();

    if($traverser->eatString("\\") === NULL){
        return NULL;
    }

    if($traverser->isEOF()){
        return new CheckedEOFEscapeToken();
    }

    $newline = $traverser->eatPattern($newlineRegex);
    if($newline !== NULL){
        return new CheckedContinuationEscapeToken($newline);
    }

    $traverser->rollback($beforeBackslash);
    return NULL;
}
