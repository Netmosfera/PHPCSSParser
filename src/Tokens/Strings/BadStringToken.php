<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class BadStringToken implements AnyStringToken
{
    private $delimiter;

    private $pieces;

    function __construct(String $delimiter, Array $pieces){
        $this->delimiter = $delimiter;
        $this->pieces = $pieces;
    }

    function __toString(): String{
        return $this->delimiter . implode("", $this->pieces);
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->delimiter, $other->delimiter) &&
            match($this->pieces, $other->pieces);
    }

    function getDelimiter(): String{
        return $this->delimiter;
    }

    function getPieces(): array{
        return $this->pieces;
    }
}
