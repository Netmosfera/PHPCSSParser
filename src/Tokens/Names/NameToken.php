<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;

/**
 * A {@see NameToken} is a "word" in the CSS language.
 *
 * It is any sequence of readable characters that is not a number, an escape sequence, an
 * operator or a generic delimiter.
 */
class NameToken implements EvaluableToken
{
    /**
     * @var         NameBitToken[]|ValidEscapeToken[]
     * `Array<Int, NameBitToken|ValidEscapeToken>`
     */
    private $_pieces;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $_intendedValue;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $_stringValue;

    /**
     * @var         Int
     * `Int`
     */
    private $_newlineCount;

    /**
     * @param       NameBitToken[]|ValidEscapeToken[] $pieces
     * `Array<Int, NameBitToken|ValidEscapeToken>`
     * The {@see NameToken}'s components.
     */
    public function __construct(array $pieces){
        $this->_pieces = $pieces;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return implode("", $this->_pieces);
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
            match($this->_pieces, $other->_pieces);
    }

    /** @inheritDoc */
    public function intendedValue(): String{ // @memo
        $intendedValue = "";
        foreach($this->_pieces as $piece){
            $intendedValue .= $piece->intendedValue();
        }
        return $intendedValue;
    }

    /**
     * Returns the {@see NameToken}'s components.
     *
     * @return      NameBitToken[]|ValidEscapeToken[]
     * `Array<Int, NameBitToken|ValidEscapeToken>`
     * Returns the {@see NameToken}'s components.
     */
    public function pieces(): array{
        return $this->_pieces;
    }
}
