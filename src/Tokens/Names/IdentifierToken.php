<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * An {@see IdentifierToken} is a root {@see NameToken}.
 *
 * The initial part of an {@see IdentifierToken} cannot be confused with a
 * {@see NumberToken}; that is, it cannot start with a digit and it cannot start with `-`
 * followed by a digit.
 */
class IdentifierToken implements IdentifierLikeToken
{
    /**
     * @var         NameToken                                                               `NameToken`
     */
    private $name;

    /**
     * @param       NameToken                               $name                           `NameToken`
     * The {@see NameToken} that is to become a {@see IdentifierToken}.
     */
    function __construct(NameToken $name){
        $this->name = $name;
    }

    /** @inheritDoc */
    function __toString(): String{
        return (String)$this->name;
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->name, $other->name);
    }

    /**
     * Returns the {@see NameToken} for this {@see IdentifierToken}.
     *
     * @returns     NameToken                                                               `NameToken`
     * Returns the {@see NameToken} for this {@see IdentifierToken}.
     */
    function getName(): NameToken{
        return $this->name;
    }
}
