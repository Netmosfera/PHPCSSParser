<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class PercentageToken implements NumericToken
{
    private $number;

    function __construct(NumberToken $number){
        $this->number = $number;
    }

    function __toString(): String{
        return $this->number . "%";
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->number, $this->number);
    }

    function getNumber(): NumberToken{
        return $this->number;
    }
}
