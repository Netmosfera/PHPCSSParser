<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see StringToken} is text delimited by `'` or `"`.
 */
class StringToken implements AnyStringToken
{
    /**
     * @var         String
     * `String`
     */
    private $delimiter;

    /**
     * @var         StringBitToken[]|EscapeToken[]
     * `Array<Int, StringBitToken|EscapeToken>`
     */
    private $pieces;

    /**
     * @var         Bool
     * `Bool`
     */
    private $terminatedWithEOF;

    /**
     * @param       String                                  $delimiter
     * `String`
     * @TODOC
     *
     * @param       StringBitToken[]|EscapeToken[]          $pieces
     * `Array<Int, StringBitToken|EscapeToken>`
     * @TODOC
     *
     * @param       Bool                                    $terminatedWithEOF
     * `Bool`
     * @TODOC
     */
    function __construct(String $delimiter, Array $pieces, Bool $terminatedWithEOF){
        foreach($pieces as $piece){ // @TODO move to CheckedStringToken
            assert($piece instanceof StringBitToken || $piece instanceof EscapeToken);
        }
        // @TODO make sure that if $pieces' last element is EOFEscape, $terminatedWithEOF
        // should be set to TRUE
        $this->delimiter = $delimiter;
        $this->pieces = $pieces;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    /** @inheritDoc */
    function __toString(): String{
        return
            $this->delimiter .
            implode("", $this->pieces) .
            ($this->terminatedWithEOF ? "" : $this->delimiter);
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->delimiter, $other->delimiter) &&
            match($this->pieces, $other->pieces) &&
            match($this->terminatedWithEOF, $other->terminatedWithEOF);
    }

    /**
     * @TODOC
     *
     * @returns     String
     * `String`
     * @TODOC
     */
    function getDelimiter(): String{
        return $this->delimiter;
    }

    /**
     * @TODOC
     *
     * @returns     StringBitToken[]|EscapeToken[]
     * `Array<Int, StringBitToken|EscapeToken>`
     * @TODOC
     */
    function getPieces(): Array{
        return $this->pieces;
    }

    /**
     * @TODOC
     *
     * @returns     Bool
     * `Bool`
     * @TODOC
     */
    function isTerminatedWithEOF(): Bool{
        return $this->isTerminatedWithEOF();
    }
}
