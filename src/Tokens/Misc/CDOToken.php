<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Token;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see CDOToken} represents the sequence of characters `<!--`.
 *
 * This token has no actual purpose; originally it was used to hide CSS code embedded in
 * HTML code to old user-agents:
 *
 * ```css
 * <style><!--
 * body { color: darkblue; }
 * --></style>
 */
class CDOToken implements Token
{
    /** @inheritDoc */
    function __toString(): String{
        return "<!--";
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return $other instanceof self;
    }
}
