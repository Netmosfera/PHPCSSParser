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
    private $_EOFTerminated;

    /**
     * @param       String $delimiter
     * `String`
     * @TODOC
     *
     * @param       StringBitToken[]|EscapeToken[] $pieces
     * `Array<Int, StringBitToken|EscapeToken>`
     * @TODOC
     *
     * @param       Bool $EOFTerminated
     * `Bool`
     * @TODOC
     */
    public function __construct(
        String $delimiter,
        array $pieces,
        Bool $EOFTerminated
    ){
        $this->_delimiter = $delimiter;
        $this->_pieces = $pieces;
        $this->_EOFTerminated = $EOFTerminated;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return
            $this->_delimiter .
            implode("", $this->_pieces) .
            ($this->_EOFTerminated ? "" : $this->_delimiter);
    }

    /** @inheritDoc */
    public function isParseError(): Bool{
        return $this->_EOFTerminated;
    }

    /** @inheritDoc */
    public function newlineCount(): Int{ // @memo
        $count = 0;
        foreach($this->_pieces as $piece){
            $count += $piece->newlineCount();
        }
        return $count;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_delimiter, $other->_delimiter) &&
            match($this->_pieces, $other->_pieces) &&
            match($this->_EOFTerminated, $other->_EOFTerminated);
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
    public function pieces(): array{
        return $this->_pieces;
    }

    /**
     * @TODOC
     *
     * @return      Bool
     * `Bool`
     * @TODOC
     */
    public function EOFTerminated(): Bool{
        return $this->_EOFTerminated;
    }

    /** @inheritDoc */
    public function intendedValue(): String{ // @memo
        $intendedValue = "";
        foreach($this->_pieces as $piece){
            $intendedValue .= $piece->intendedValue();
        }
        return $intendedValue;
    }
}
