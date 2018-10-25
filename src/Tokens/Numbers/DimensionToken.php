<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Numbers;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;

/**
 * A {@see DimensionToken} is a number followed by a unit of measurement.
 */
class DimensionToken implements NumericToken
{
    /**
     * @var         NumberToken
     * `NumberToken`
     */
    private $_number;

    /**
     * @var         IdentifierToken
     * `IdentifierToken`
     */
    private $_unit;

    /**
     * @param       NumberToken $number
     * `NumberToken`
     * The number.
     *
     * @param       IdentifierToken $unit
     * `IdentifierToken`
     * The unit of measurement.
     */
    public function __construct(NumberToken $number, IdentifierToken $unit){
        $this->_number = $number;
        $this->_unit = $unit;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return $this->_number . $this->_unit;
    }

    /** @inheritDoc */
    public function isParseError(): Bool{
        return FALSE;
    }

    /** @inheritDoc */
    public function newlineCount(): Int{
        return $this->_unit->newlineCount();
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_number, $this->_number) &&
            match($other->_unit, $this->_unit);
    }

    /**
     * Returns the number.
     *
     * @return      NumberToken
     * `NumberToken`
     * Returns the number.
     */
    public function number(): NumberToken{
        return $this->_number;
    }

    /**
     * Returns the unit of measurement.
     *
     * @return      IdentifierToken
     * `IdentifierToken`
     * Returns the unit of measurement.
     */
    public function unit(): IdentifierToken{
        return $this->_unit;
    }
}
