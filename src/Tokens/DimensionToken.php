<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class DimensionToken
{
    public $number;

    public $name;

    function __construct(NumberToken $number, IdentifierToken $name){
        $this->number = $number;
        $this->name = $name;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->number, $this->number) &&
            match($other->name, $this->name);
    }
}
