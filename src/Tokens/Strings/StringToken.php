<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;

/**
 * A {@see StringToken} is text delimited by `'` or `"`.
 */
class StringToken implements AnyStringToken
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
     * @var         Bool
     * `Bool`
     */
    private $_precedesEOF;

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
    public function __construct(
        String $delimiter,
        Array $pieces,
        Bool $terminatedWithEOF
    ){
        $this->_delimiter = $delimiter;
        $this->_pieces = $pieces;
        $this->_precedesEOF = $terminatedWithEOF;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return
            $this->_delimiter .
            implode("", $this->_pieces) .
            ($this->_precedesEOF ? "" : $this->_delimiter);
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_delimiter, $other->_delimiter) &&
            match($this->_pieces, $other->_pieces) &&
            match($this->_precedesEOF, $other->_precedesEOF);
    }

    /**
     * @TODOC
     *
     * @return      String
     * `String`
     * @TODOC
     */
    public function getDelimiter(): String{
        return $this->_delimiter;
    }

    /**
     * @TODOC
     *
     * @return      StringBitToken[]|EscapeToken[]
     * `Array<Int, StringBitToken|EscapeToken>`
     * @TODOC
     */
    public function getPieces(): Array{
        return $this->_pieces;
    }

    /**
     * @TODOC
     *
     * @return      Bool
     * `Bool`
     * @TODOC
     */
    public function precedesEOF(): Bool{
        return $this->_precedesEOF;
    }
}
