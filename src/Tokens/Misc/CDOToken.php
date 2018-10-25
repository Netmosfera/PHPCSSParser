<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

use Netmosfera\PHPCSSAST\Tokens\RootToken;

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
class CDOToken implements RootToken
{
    /** @inheritDoc */
    public function __toString(): String{
        return "<!--";
    }

    /** @inheritDoc */
    public function isParseError(): Bool{
        return FALSE;
    }

    /** @inheritDoc */
    public function newlineCount(): Int{
        return 0;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return $other instanceof self;
    }
}
