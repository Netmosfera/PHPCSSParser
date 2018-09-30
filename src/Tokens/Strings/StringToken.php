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
    public function __construct(
        String $delimiter,
        Array $pieces,
        Bool $terminatedWithEOF
    ){
        $this->delimiter = $delimiter;
        $this->pieces = $pieces;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return
            $this->delimiter .
            implode("", $this->pieces) .
            ($this->terminatedWithEOF ? "" : $this->delimiter);
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->delimiter, $other->delimiter) &&
            match($this->pieces, $other->pieces) &&
            match($this->terminatedWithEOF, $other->terminatedWithEOF);
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

    /**
     * @TODOC
     *
     * @return      Bool
     * `Bool`
     * @TODOC
     */
    public function isTerminatedWithEOF(): Bool{
        return $this->isTerminatedWithEOF();
    }
}
