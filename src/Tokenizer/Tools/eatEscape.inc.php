<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\has;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\ActualEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\PlainEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\EOFEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\Escape;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Eats a string escape sequence.
 *
 * Assumes that the `\` code point has been consumed already.
 */
function eatEscape(Traverser $t): Escape{
    if($t->isEOF()){
        return new EOFEscape();
    }

    $newline = eatNewline($t);
    if(has($newline)){
        return new PlainEscape($newline);
    }

    assert(isValidEscapeCodePoint($t));

    $hexDigits = $t->eatExp('[a-fA-F0-9]{1,6}');

    if(has($hexDigits)){
        $whitespace = eatWhitespace($t);
        return new ActualEscape($hexDigits, $whitespace);
    }

    return new PlainEscape($t->eatExp('.'));
}
