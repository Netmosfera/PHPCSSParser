<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class LeftSquareBracket
{
    function equals($other): Bool{
        return $other instanceof self;
    }
}
