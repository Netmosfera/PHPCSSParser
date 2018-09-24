<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Token;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CDOToken implements Token
{
    function __toString(): String{
        return "<!--";
    }

    function equals($other): Bool{
        return $other instanceof self;
    }
}
