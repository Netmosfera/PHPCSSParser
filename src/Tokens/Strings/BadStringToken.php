<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;

/**
 * A {@see BadStringToken} is {@see StringToken} terminated with a newline.
 *
 * Newlines are not allowed in a {@see StringToken}, unless they appear as a
 * escape sequence.
 *
 * Strings terminated with EOF are not considered "bad" (i.e. not usable), but
 * they are considered parse errors.
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
    public function __construct(String $delimiter, Array $pieces){
        $this->delimiter = $delimiter;
        $this->pieces = $pieces;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return $this->delimiter . implode("", $this->pieces);
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->delimiter, $other->delimiter) &&
            match($this->pieces, $other->pieces);
    }

    /**
     * @TODOC
     *
     * @return      String
     * `String`
     * @TODOC
     */
    public function getDelimiter(): String{
        return $this->delimiter;
    }

    /**
     * @TODOC
     *
     * @return      StringBitToken[]|EscapeToken[]
     * `Array<Int, StringBitToken|EscapeToken>`
     * @TODOC
     */
    public function getPieces(): Array{
        return $this->pieces;
    }
}
