<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class PercentageToken
{
    public $number;

    function __construct(NumberToken $number){
        $this->number = $number;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->number, $this->number);
    }
}
