<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Escapes;

/**
 * A {@see EOFEscapeToken} is `\` followed by no more data.
 */
class EOFEscapeToken implements NullEscapeToken
{
    /** @inheritDoc */
    public function equals($other): Bool{
        return $other instanceof self;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return "\\";
    }

    /** @inheritDoc */
    public function intendedValue(): String{
        return "";
    }
}
