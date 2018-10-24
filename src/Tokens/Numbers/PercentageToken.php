<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Numbers;

use function Netmosfera\PHPCSSAST\match;

/**
 * A {@see PercentageToken} is a number followed by `%`.
 */
class PercentageToken implements NumericToken
{
    /**
     * @var         NumberToken
     * `NumberToken`
     */
    private $_number;

    /**
     * @param       NumberToken $number
     * `NumberToken`
     * The {@see NumberToken} that is to represent a percentage.
     */
    public function __construct(NumberToken $number){
        $this->_number = $number;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return $this->_number . "%";
    }

    /** @inheritDoc */
    public function newlineCount(): Int{
        return 0;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_number, $this->_number);
    }

    /**
     * Returns the {@see NumberToken} represents the percentage.
     *
     * @return      NumberToken
     * `NumberToken`
     * Returns the {@see NumberToken} represents the percentage.
     */
    public function number(): NumberToken{
        return $this->_number;
    }
}
