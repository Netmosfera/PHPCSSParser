<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;

function eatValidEscapeToken(
    Traverser $traverser,
    String $hexDigitRegexSet,
    String $whitespaceRegex,
    String $newlineRegexSet,
    String $WhitespaceTokenClass = CheckedWhitespaceToken::CLASS,
    String $CodePointEscapeTokenClass = CheckedCodePointEscapeToken::CLASS,
    String $EncodedCodePointEscapeTokenClass = CheckedEncodedCodePointEscapeToken::CLASS
): ?ValidEscapeToken{

    $beforeBackslash = $traverser->index;

    if($traverser->eatString("\\") === NULL){
        return NULL;
    }

    if(isset($traverser->data[$traverser->index]));else{
        $traverser->index = $beforeBackslash;
        return NULL;
    }

    $hexDigits = $traverser->eatPattern('[' . $hexDigitRegexSet . ']{1,6}');

    if(isset($hexDigits)){
        $whitespaceText = $traverser->eatPattern($whitespaceRegex);

        if(isset($whitespaceText)){
            $whitespace = new $WhitespaceTokenClass($whitespaceText);
        }else{
            $whitespace = NULL;
        }

        return new $CodePointEscapeTokenClass($hexDigits, $whitespace);
    }

    $beforeNewline = $traverser->createBranch();
    $codePoint = $beforeNewline->eatLength(1);
    $ws = ["\n" => TRUE, "\r" => TRUE, "\f" => TRUE];
    if(isset($ws[$codePoint])){
        $codePoint = NULL;
    }else{
        $traverser->importBranch($beforeNewline);
    }

    if(isset($codePoint)){
        return new $EncodedCodePointEscapeTokenClass($codePoint);
    }

    $traverser->index = $beforeBackslash;
    return NULL;
}
