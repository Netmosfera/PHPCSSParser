<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Nodes\Components\Component;

/**
 * An {@see IdentifierToken} is a root {@see NameToken}.
 *
 * The initial part of an {@see IdentifierToken} cannot be confused with a
 * {@see NumberToken}; that is, it cannot start with a digit and it cannot start with `-`
 * followed by a digit.
 */
class IdentifierToken implements IdentifierLikeToken, Component
{
    /**
     * @var         NameToken
     * `NameToken`
     */
    private $_name;

    /**
     * @param       NameToken $name
     * `NameToken`
     * The {@see NameToken} to become a {@see IdentifierToken}.
     */
    public function __construct(NameToken $name){
        $this->_name = $name;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return (String)$this->_name;
    }

    /** @inheritDoc */
    public function isParseError(): Bool{
        return FALSE;
    }

    /** @inheritDoc */
    public function newlineCount(): Int{ // @memo
        return $this->_name->newlineCount();
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_name, $other->_name);
    }

    /**
     * Returns the {@see NameToken} for this {@see IdentifierToken}.
     *
     * @return      NameToken
     * `NameToken`
     * Returns the {@see NameToken} for this {@see IdentifierToken}.
     */
    public function name(): NameToken{
        return $this->_name;
    }
}
