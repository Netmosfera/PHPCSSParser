<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class Whitespaces
{
    public $whitespaces;

    function __construct(String $text){
        $this->whitespaces = $text;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            $other->whitespaces === $this->whitespaces;
    }
}
