<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\has;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatWhitespace;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\ActualEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\PlainEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\Escape;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Consumes valid escape sequences.
 *
 * Assumes that `\` has been consumed already.
 */
function eatValidEscape(Traverser $t): Escape{
    if(isValidEscape($t)){
        $hexDigits = $t->eatExp('[a-fA-F0-9]{1,6}');
        if(has($hexDigits)){
            $whitespace = eatWhitespace($t);
            return new ActualEscape($hexDigits, $whitespace);
        }
        return new PlainEscape($t->eatExp('.'));
    }
    return NULL;
}
