<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Operators;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Token;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see LeftSquareBracketToken} represents the character `[`.
 */
class LeftSquareBracketToken implements Token
{
    /** @inheritDoc */
    function __toString(): String{
        return "[";
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return $other instanceof self;
    }
}
