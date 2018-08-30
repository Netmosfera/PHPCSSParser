<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

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
            match($other->whitespaces, $this->whitespaces);
    }
}
