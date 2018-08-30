<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class URLToken
{
    public $whitespaceBefore;

    public $pieces;

    public $terminatedWithEOF;

    public $whitespaceAfter;

    function __construct(
        String $whitespaceBefore,
        Array $pieces,
        Bool $terminatedWithEOF,
        String $whitespaceAfter
    ){
        $this->whitespaceBefore = $whitespaceBefore;
        $this->pieces = $pieces;
        $this->terminatedWithEOF = $terminatedWithEOF;
        $this->whitespaceAfter = $whitespaceAfter;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->whitespaceBefore, $other->whitespaceBefore) &&
            match($this->pieces, $other->pieces) &&
            match($this->terminatedWithEOF, $other->terminatedWithEOF) &&
            match($this->whitespaceAfter, $other->whitespaceAfter);
    }
}
