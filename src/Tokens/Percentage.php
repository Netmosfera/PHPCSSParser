<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class Percentage
{
    public $number;

    function __construct(_Number $number){
        $this->number = $number;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            $other->number === $this->number;
    }
}
