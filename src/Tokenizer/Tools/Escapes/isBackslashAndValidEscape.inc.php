<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\has;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * -
 *
 * A valid escape is a sequence of characters that has effect in a css string.
 * These are valid escapes according to the spec:
 *
 * - `\6e` results in `n`
 * - `\x` results in `x`
 * - `\Æ` results in `Æ`
 *
 * The only exceptions are `\` followed by any newline sequence or `\` followed by EOF.
 * These are not necessarily parse errors; for example `\` followed by newline in a CSS
 * string is simply ignored.
 */
function isBackslashAndValidEscape(Traverser $t): Bool{
    $t = $t->createBranch();
    return has($t->eatStr("\\")) && isValidEscape($t);
}
