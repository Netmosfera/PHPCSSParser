<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\Escape;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * String Token.
 */
class StringToken implements AnyStringToken
{
    private $delimiter;

    private $pieces;

    private $terminatedWithEOF;

    function __construct(String $delimiter, Array $pieces, Bool $terminatedWithEOF){
        foreach($pieces as $piece){ // @TODO move to CheckedStringToken
            assert($piece instanceof StringBitToken || $piece instanceof Escape);
        }
        // @TODO make sure that if $pieces' last element is EOFEscape, $terminatedWithEOF
        // should be set to TRUE
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

    function getPieces(): Array{
        return $this->pieces;
    }

    function isTerminatedWithEOF(): Bool{
        return $this->isTerminatedWithEOF();
    }
}
