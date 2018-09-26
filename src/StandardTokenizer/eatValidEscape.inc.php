<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscape;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscape;
use Netmosfera\PHPCSSAST\Tokens\Escapes\HexEscape;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Consumes a {@see ValidEscape}, if any.
 */
function eatValidEscape(
    Traverser $traverser,
    String $hexDigitRegExpSet,
    String $whitespaceRegExp,
    String $newlineRegExpSet
): ?ValidEscape{

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
        return new HexEscape($hexDigits, $whitespace);
    }

    $codePoint = $traverser->eatExp('[^' . $newlineRegExpSet . ']');
    if($codePoint !== NULL){
        return new CodePointEscape($codePoint);
    }

    $traverser->rollback($beforeBackslash);
    return NULL;
}
