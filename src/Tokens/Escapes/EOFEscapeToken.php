<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A backslash followed by EOF.
 */
class EOFEscapeToken implements NullEscapeToken
{
    function equals($other): Bool{
        return $other instanceof self;
    }

    function __toString(): String{
        return "\\";
    }

    function getValue(): String{
        return "";
    }
}
