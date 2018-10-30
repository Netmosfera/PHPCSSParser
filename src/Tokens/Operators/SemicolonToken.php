<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Operators;

use Netmosfera\PHPCSSAST\Tokens\RootToken;

class SemicolonToken implements RootToken
{
    /** @inheritDoc */
    public function __toString(): String{
        return ";";
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
    function equals($other): Bool{
        return $other instanceof self;
    }
}
