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
    private $_delimiter;

    /**
     * @var         StringBitToken[]|EscapeToken[]
     * `Array<Int, StringBitToken|EscapeToken>`
     */
    private $_pieces;

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
        $this->_delimiter = $delimiter;
        $this->_pieces = $pieces;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return $this->_delimiter . implode("", $this->_pieces);
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_delimiter, $other->_delimiter) &&
            match($this->_pieces, $other->_pieces);
    }

    /**
     * @TODOC
     *
     * @return      String
     * `String`
     * @TODOC
     */
    public function delimiter(): String{
        return $this->_delimiter;
    }

    /**
     * @TODOC
     *
     * @return      StringBitToken[]|EscapeToken[]
     * `Array<Int, StringBitToken|EscapeToken>`
     * @TODOC
     */
    public function pieces(): Array{
        return $this->_pieces;
    }
}
