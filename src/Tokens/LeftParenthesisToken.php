<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class LeftParenthesisToken
{
    function equals($other): Bool{
        return $other instanceof self;
    }
}
