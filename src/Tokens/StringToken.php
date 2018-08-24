<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class StringToken
{
    public $pieces;

    public $terminatedWithEOF;

    function __construct(Array $pieces, Bool $terminatedWithEOF = FALSE){
        $this->pieces = $pieces;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->pieces, $other->pieces);
    }
}
