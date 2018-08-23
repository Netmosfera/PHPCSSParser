<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

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
 *
 * These are invalid escapes despite they are not a parse error - that is, they are
 * simply ignored in css strings:
 *
 * `"\\\n"`, `"\\\r"`, `"\\\r\n"`, `"\\\f"`
 */
function isValidEscape(Traverser $t): Bool{
    $t = $t->createBranch();
    return has($t->eatStr("\\")) && isValidEscapeCodePoint($t);
}
