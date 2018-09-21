<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class DimensionToken implements NumericToken
{
    private $number;

    private $unit;

    function __construct(NumberToken $number, IdentifierToken $unit){
        $this->number = $number;
        $this->unit = $unit;
    }

    function __toString(): String{
        return $this->number . $this->unit;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->number, $this->number) &&
            match($other->unit, $this->unit);
    }

    function getNumber(): NumberToken{
        return $this->number;
    }

    function getUnit(): IdentifierToken{
        return $this->unit;
    }
}
