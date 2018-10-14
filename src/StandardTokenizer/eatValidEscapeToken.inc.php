<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;

function eatValidEscapeToken(
    Traverser $traverser,
    String $hexDigitRegexSet,
    String $whitespaceRegex,
    String $newlineRegexSet
): ?ValidEscapeToken{

    $beforeBackslash = $traverser->savepoint();

    if($traverser->eatString("\\") === NULL){
        return NULL;
    }

    if($traverser->isEOF()){
        $traverser->rollback($beforeBackslash);
        return NULL;
    }

    $hexDigits = $traverser->eatPattern('[' . $hexDigitRegexSet . ']{1,6}');

    if($hexDigits !== NULL){
        $whitespaceText = $traverser->eatPattern($whitespaceRegex);

        if(isset($whitespaceText)){
            $whitespace = new CheckedWhitespaceToken($whitespaceText);
        }else{
            $whitespace = NULL;
        }

        return new CheckedCodePointEscapeToken($hexDigits, $whitespace);
    }

    // $codePoint = $traverser->eatPattern('[^' . $newlineRegexSet . ']');

    $beforeNewline = $traverser->createBranch();
    $codePoint = $beforeNewline->eatLength(1);
    $ws = ["\n" => TRUE, "\r" => TRUE, "\f" => TRUE];
    if(isset($ws[$codePoint])){
        $codePoint = NULL;
    }else{
        $traverser->importBranch($beforeNewline);
    }

    if($codePoint !== NULL){
        return new CheckedEncodedCodePointEscapeToken($codePoint);
    }

    $traverser->rollback($beforeBackslash);
    return NULL;
}
