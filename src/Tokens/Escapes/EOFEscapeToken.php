<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see EOFEscapeToken} is `\` followed by no more data.
 */
class EOFEscapeToken implements NullEscapeToken
{
    /** @inheritDoc */
    function equals($other): Bool{
        return $other instanceof self;
    }

    /** @inheritDoc */
    function __toString(): String{
        return "\\";
    }

    /** @inheritDoc */
    function getValue(): String{
        return "";
    }
}
