<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Consumes a {@see ValidEscapeToken}, if any.
 */
function eatValidEscapeToken(
    Traverser $traverser,
    String $hexDigitRegExpSet,
    String $whitespaceRegExp,
    String $newlineRegExpSet
): ?ValidEscapeToken{

    $beforeBackslash = $traverser->savepoint();

    if($traverser->eatStr("\\") === NULL){
        return NULL;
    }

    if($traverser->isEOF()){
        $traverser->rollback($beforeBackslash);
        return NULL;
    }

    $hexDigits = $traverser->eatExp('[' . $hexDigitRegExpSet . ']{1,6}');
    if($hexDigits !== NULL){
        $whitespace = $traverser->eatExp($whitespaceRegExp);
        $whitespace = $whitespace === NULL ? NULL : new WhitespaceToken($whitespace);
        return new CodePointEscapeToken($hexDigits, $whitespace);
    }

    $codePoint = $traverser->eatExp('[^' . $newlineRegExpSet . ']');
    if($codePoint !== NULL){
        return new EncodedCodePointEscapeToken($codePoint);
    }

    $traverser->rollback($beforeBackslash);
    return NULL;
}
