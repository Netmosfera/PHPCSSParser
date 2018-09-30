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
    private $number;

    /**
     * @param       NumberToken                             $number
     * `NumberToken`
     * The {@see NumberToken} that is to represent a percentage.
     */
    public function __construct(NumberToken $number){
        $this->number = $number;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return $this->number . "%";
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->number, $this->number);
    }

    /**
     * Returns the {@see NumberToken} represents the percentage.
     *
     * @return      NumberToken
     * `NumberToken`
     * Returns the {@see NumberToken} represents the percentage.
     */
    public function getNumber(): NumberToken{
        return $this->number;
    }
}
