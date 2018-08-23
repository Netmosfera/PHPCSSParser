<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A backslash followed by EOF.
 */
class EOFEscape implements Escape
{
    function equals($other): Bool{
        return $other instanceof self;
    }
}
