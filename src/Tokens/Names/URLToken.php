<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use function implode;
use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\Escape;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class URLToken implements AnyURLToken
{
    private $whitespaceBefore;

    private $pieces;

    private $whitespaceAfter;

    private $terminatedWithEOF;

    function __construct(
        String $whitespaceBefore,
        Array $pieces,
        String $whitespaceAfter,
        Bool $terminatedWithEOF
    ){
        $this->whitespaceBefore = $whitespaceBefore;
        $this->pieces = $pieces;
        $this->whitespaceAfter = $whitespaceAfter;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    function __toString(): String{
        return
            "url(" .
            $this->whitespaceBefore .
            implode("", $this->pieces) .
            $this->whitespaceAfter .
            ($this->terminatedWithEOF ? "" : ")");
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->whitespaceBefore, $other->whitespaceBefore) &&
            match($this->pieces, $other->pieces) &&
            match($this->whitespaceAfter, $other->whitespaceAfter) &&
            match($this->terminatedWithEOF, $other->terminatedWithEOF);
    }

    function getWhitespaceBefore(): String{
        return $this->whitespaceBefore;
    }

    function getPieces(): array{
        return $this->pieces;
    }

    function getWhitespaceAfter(): String{
        return $this->whitespaceAfter;
    }

    function isTerminatedWithEOF(): Bool{
        return $this->terminatedWithEOF;
    }
}
