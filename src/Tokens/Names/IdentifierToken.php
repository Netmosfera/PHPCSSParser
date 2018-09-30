<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names;

use function Netmosfera\PHPCSSAST\match;

/**
 * An {@see IdentifierToken} is a root {@see NameToken}.
 *
 * The initial part of an {@see IdentifierToken} cannot be confused with a
 * {@see NumberToken}; that is, it cannot start with a digit and it cannot start
 * with `-` followed by a digit.
 */
class IdentifierToken implements IdentifierLikeToken
{
    /**
     * @var         NameToken
     * `NameToken`
     */
    private $name;

    /**
     * @param       NameToken                               $name
     * `NameToken`
     * The {@see NameToken} to become a {@see IdentifierToken}.
     */
    public function __construct(NameToken $name){
        $this->name = $name;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return (String)$this->name;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->name, $other->name);
    }

    /**
     * Returns the {@see NameToken} for this {@see IdentifierToken}.
     *
     * @return      NameToken
     * `NameToken`
     * Returns the {@see NameToken} for this {@see IdentifierToken}.
     */
    public function getName(): NameToken{
        return $this->name;
    }
}
