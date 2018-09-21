<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class StringToken implements AnyStringToken
{
    private $delimiter;

    private $pieces;

    private $terminatedWithEOF;

    function __construct(String $delimiter, Array $pieces, Bool $terminatedWithEOF = FALSE){
        $this->delimiter = $delimiter;
        $this->pieces = $pieces;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    function __toString(): String{
        return
            $this->delimiter .
            implode("", $this->pieces) .
            ($this->terminatedWithEOF ? "" : $this->delimiter);
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->delimiter, $other->delimiter) &&
            match($this->pieces, $other->pieces) &&
            match($this->terminatedWithEOF, $other->terminatedWithEOF);
    }

    function getDelimiter(): String{
        return $this->delimiter;
    }

    function getPieces(): array{
        return $this->pieces;
    }

    function isTerminatedWithEOF(): Bool{
        return $this->isTerminatedWithEOF();
    }
}
