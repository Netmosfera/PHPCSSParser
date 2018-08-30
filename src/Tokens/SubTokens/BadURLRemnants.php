<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\SubTokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class BadURLRemnants
{
    public $pieces;

    function __construct(Array $pieces){
        $this->pieces = $pieces;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            $this->pieces === $other->pieces;
    }
}
