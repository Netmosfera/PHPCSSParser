<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Operators;

use Netmosfera\PHPCSSAST\Tokens\Token;

/**
 * A {@see LeftSquareBracketToken} represents the character `[`.
 */
class LeftSquareBracketToken implements Token
{
    /** @inheritDoc */
    public function __toString(): String{
        return "[";
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return $other instanceof self;
    }
}
