<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscape;
use Netmosfera\PHPCSSAST\Tokens\Escapes\NullEscape;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscape;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Consumes a {@see NullEscape}, if any.
 */
function eatNullEscape(
    Traverser $traverser,
    String $newlineRegExp
): ?NullEscape{

    $beforeBackslash = $traverser->savepoint();

    if($traverser->eatStr("\\") === NULL){
        return NULL;
    }

    if($traverser->isEOF()){
        return new EOFEscape();
    }

    $newline = $traverser->eatExp($newlineRegExp);
    if($newline !== NULL){
        return new ContinuationEscape($newline);
    }

    $traverser->rollback($beforeBackslash);
    return NULL;
}
