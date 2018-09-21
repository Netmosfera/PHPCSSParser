<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Token;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class WhitespaceToken implements Token
{
    private $whitespaces;

    function __construct(String $whitespaces){
        $this->whitespaces = $whitespaces;
    }

    function __toString(): String{
        return $this->whitespaces;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->whitespaces, $this->whitespaces);
    }
}
