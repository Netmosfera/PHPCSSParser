<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;

function eatValidEscapeToken(
    Traverser $traverser,
    String $hexDigitRegexSet = SpecData::HEX_DIGITS_REGEX_SET,
    String $whitespaceRegex = SpecData::WHITESPACES_REGEX_SEQS,
    String $newlineRegexSet = "not used",
    String $WhitespaceTokenClass = WhitespaceToken::CLASS,
    String $CodePointEscapeTokenClass = CodePointEscapeToken::CLASS,
    String $EncodedCodePointEscapeTokenClass = EncodedCodePointEscapeToken::CLASS
): ?ValidEscapeToken{

    if(isset($hexDigitRegexSet));else{
        $hexDigitRegexSet = SpecData::HEX_DIGITS_REGEX_SET;
    }

    if(isset($whitespaceRegex));else{
        $whitespaceRegex = SpecData::WHITESPACES_REGEX_SEQS;
    }

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
