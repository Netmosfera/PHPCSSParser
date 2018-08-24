<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class WhitespaceToken
{
    public $whitespaces;

    function __construct(String $whitespaces){
        $this->whitespaces = $whitespaces;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            $other->whitespaces === $this->whitespaces;
    }
}
