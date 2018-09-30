<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Operators;

use Netmosfera\PHPCSSAST\Tokens\Token;

/**
 * A {@see RightCurlyBracketToken} represents the character `}`.
 */
class RightCurlyBracketToken implements Token
{
    /** @inheritDoc */
    public function __toString(): String{
        return "}";
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return $other instanceof self;
    }
}
