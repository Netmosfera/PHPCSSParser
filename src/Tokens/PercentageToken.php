<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

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
            $other->number === $this->number;
    }
}
