<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see BadStringToken} is {@see StringToken} terminated with a newline.
 *
 * Newlines are not allowed in a {@see StringToken}, unless they appear as a escape
 * sequence.
 *
 * Strings terminated with EOF are not considered "bad" (i.e. not usable), but they are
 * considered parse errors.
 */
class BadStringToken implements AnyStringToken
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
     * @param       String                                  $delimiter
     * `String`
     * @TODOC
     *
     * @param       StringBitToken[]|EscapeToken[]          $pieces
     * `Array<Int, StringBitToken|EscapeToken>`
     * @TODOC
     */
    function __construct(String $delimiter, Array $pieces){
        foreach($pieces as $piece){ // @TODO move to CheckedStringToken
            assert($piece instanceof StringBitToken || $piece instanceof EscapeToken);
        }
        $this->delimiter = $delimiter;
        $this->pieces = $pieces;
    }

    /** @inheritDoc */
    function __toString(): String{
        return $this->delimiter . implode("", $this->pieces);
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->delimiter, $other->delimiter) &&
            match($this->pieces, $other->pieces);
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
}
