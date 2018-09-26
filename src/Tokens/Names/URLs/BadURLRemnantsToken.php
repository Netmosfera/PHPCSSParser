<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\Escape;
use Netmosfera\PHPCSSAST\Tokens\Token;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class BadURLRemnantsToken implements Token
{
    /** @var String[]|Escape[] */
    private $pieces;

    private $terminatedWithEOF;

    function __construct(Array $pieces, Bool $terminatedWithEOF = FALSE){
        $this->pieces = $pieces;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    function __toString(): String{
        return
            implode("", $this->pieces) .
            ($this->terminatedWithEOF ? "" : ")");
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->pieces, $other->pieces) &&
            match($this->terminatedWithEOF, $other->terminatedWithEOF);
    }

    function getPieces(): array{
        return $this->pieces;
    }

    function isTerminatedWithEOF(): Bool{
        return $this->terminatedWithEOF;
    }
}
