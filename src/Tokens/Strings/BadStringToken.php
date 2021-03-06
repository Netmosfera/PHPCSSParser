<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;

/**
 * A {@see BadStringToken} is {@see StringToken} terminated with a newline.
 *
 * Newlines are not allowed in a {@see StringToken}, unless they appear as a escape
 * sequence.
 *
 * N.B. Strings terminated with EOF are not considered "bad", but they are considered
 * parse errors.
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
     * @param       String $delimiter
     * `String`
     * @TODOC
     *
     * @param       StringBitToken[]|ValidEscapeToken[]|ContinuationEscapeToken[] $pieces
     * `Array<Int, StringBitToken|ValidEscapeToken|ContinuationEscapeToken>`
     * @TODOC
     */
    public function __construct(String $delimiter, array $pieces){
        $this->_delimiter = $delimiter;
        $this->_pieces = $pieces;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return $this->_delimiter . implode("", $this->_pieces);
    }

    /** @inheritDoc */
    public function isParseError(): Bool{
        return TRUE;
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
    public function pieces(): array{
        return $this->_pieces;
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
