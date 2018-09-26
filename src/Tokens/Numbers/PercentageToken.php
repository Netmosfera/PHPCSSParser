<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see PercentageToken} is a number followed by `%`.
 */
class PercentageToken implements NumericToken
{
    /**
     * @var         NumberToken                                                             `NumberToken`
     */
    private $number;

    /**
     * @param       NumberToken                             $number                         `NumberToken`
     * The {@see NumberToken} that is to represent a percentage.
     */
    function __construct(NumberToken $number){
        $this->number = $number;
    }

    /** @inheritDoc */
    function __toString(): String{
        return $this->number . "%";
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->number, $this->number);
    }

    /**
     * Returns the {@see NumberToken} represents the percentage.
     *
     * @returns     NumberToken                                                             `NumberToken`
     * Returns the {@see NumberToken} represents the percentage.
     */
    function getNumber(): NumberToken{
        return $this->number;
    }
}
