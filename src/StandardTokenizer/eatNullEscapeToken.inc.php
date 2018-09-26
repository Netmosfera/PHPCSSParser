<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\NullEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Consumes a {@see NullEscapeToken}, if any.
 */
function eatNullEscapeToken(
    Traverser $traverser,
    String $newlineRegExp
): ?NullEscapeToken{

    $beforeBackslash = $traverser->savepoint();

    if($traverser->eatStr("\\") === NULL){
        return NULL;
    }

    if($traverser->isEOF()){
        return new EOFEscapeToken();
    }

    $newline = $traverser->eatExp($newlineRegExp);
    if($newline !== NULL){
        return new ContinuationEscapeToken($newline);
    }

    $traverser->rollback($beforeBackslash);
    return NULL;
}
