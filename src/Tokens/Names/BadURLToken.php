<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class BadURLToken implements AnyURLToken
{
    private $whitespaceBefore;

    private $pieces;

    private $badURLRemnants;

    function __construct(
        String $whitespaceBefore,
        Array $pieces,
        BadURLRemnantsToken $badURLRemnants
    ){
        $this->whitespaceBefore = $whitespaceBefore;
        $this->pieces = $pieces;
        $this->badURLRemnants = $badURLRemnants;
    }

    function __toString(): String{
        return
            "url(" .
            $this->whitespaceBefore .
            implode("", $this->pieces) .
            $this->badURLRemnants;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->whitespaceBefore, $other->whitespaceBefore) &&
            match($this->pieces, $other->pieces) &&
            match($this->badURLRemnants, $other->badURLRemnants);
    }

    function getWhitespaceBefore(): String{
        return $this->whitespaceBefore;
    }

    function getPieces(): array{
        return $this->pieces;
    }

    function getRemnants(): BadURLRemnantsToken{
        return $this->badURLRemnants;
    }
}
