<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class BadStringToken
{
    public $pieces;

    function __construct(Array $pieces){
        $this->pieces = $pieces;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->pieces, $other->pieces);
    }
}
