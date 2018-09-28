<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Escapes\NullEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatNullEscapeToken(
    Traverser $traverser,
    String $newlineRegExp
): ?NullEscapeToken{

    $beforeBackslash = $traverser->savepoint();

    if($traverser->eatStr("\\") === NULL){
        return NULL;
    }

    if($traverser->isEOF()){
        return new CheckedEOFEscapeToken();
    }

    $newline = $traverser->eatExp($newlineRegExp);
    if($newline !== NULL){
        return new CheckedContinuationEscapeToken($newline);
    }

    $traverser->rollback($beforeBackslash);
    return NULL;
}
