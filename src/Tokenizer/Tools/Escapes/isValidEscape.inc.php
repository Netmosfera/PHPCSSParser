<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\hasNo;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNewline;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Checks weather the stream starts with a valid escape.
 *
 * Assumes that `\` has been consumed already.
 */
function isValidEscape(Traverser $t): Bool{
    $t = $t->createBranch();
    return $t->isNotEOF() && hasNo(eatNewline($t));
}
