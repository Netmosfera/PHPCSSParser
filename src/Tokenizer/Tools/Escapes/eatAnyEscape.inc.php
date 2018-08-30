<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\has;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNewline;
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
function eatAnyEscape(Traverser $t): Escape{
    if($t->isEOF()){
        return new EOFEscape();
    }

    $newline = eatNewline($t);
    if(has($newline)){
        return new PlainEscape($newline);
    }

    return eatValidEscape($t);
}
