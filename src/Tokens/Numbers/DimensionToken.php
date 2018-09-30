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
    private $number;

    /**
     * @var         IdentifierToken
     * `IdentifierToken`
     */
    private $unit;

    /**
     * @param       NumberToken                             $number
     * `NumberToken`
     * The number.
     *
     * @param       IdentifierToken                         $unit
     * `IdentifierToken`
     * The unit of measurement.
     */
    public function __construct(NumberToken $number, IdentifierToken $unit){
        $this->number = $number;
        $this->unit = $unit;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return $this->number . $this->unit;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->number, $this->number) &&
            match($other->unit, $this->unit);
    }

    /**
     * Returns the number.
     *
     * @return      NumberToken
     * `NumberToken`
     * Returns the number.
     */
    public function getNumber(): NumberToken{
        return $this->number;
    }

    /**
     * Returns the unit of measurement.
     *
     * @return      IdentifierToken
     * `IdentifierToken`
     * Returns the unit of measurement.
     */
    public function getUnit(): IdentifierToken{
        return $this->unit;
    }
}
