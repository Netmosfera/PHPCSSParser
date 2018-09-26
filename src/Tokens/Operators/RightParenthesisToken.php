<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Operators;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Token;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see RightParenthesisToken} represents the character `)`.
 */
class RightParenthesisToken implements Token
{
    /** @inheritDoc */
    function __toString(): String{
        return ")";
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return $other instanceof self;
    }
}
