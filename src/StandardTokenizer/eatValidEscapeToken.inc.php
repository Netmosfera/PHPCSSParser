<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCPEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCPEscapeToken;

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
        $whitespace = $whitespace === NULL ? NULL :
            new CheckedWhitespaceToken($whitespace);
        return new CheckedCPEscapeToken($hexDigits, $whitespace);
    }

    $codePoint = $traverser->eatExp('[^' . $newlineRegExpSet . ']');
    if($codePoint !== NULL){
        return new CheckedEncodedCPEscapeToken($codePoint);
    }

    $traverser->rollback($beforeBackslash);
    return NULL;
}
