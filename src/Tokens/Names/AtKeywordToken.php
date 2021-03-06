<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names;

use Netmosfera\PHPCSSAST\Nodes\Components\Component;
use Netmosfera\PHPCSSAST\Tokens\RootToken;
use function Netmosfera\PHPCSSAST\match;

/**
 * A {@see AtKeywordToken} is an {@see IdentifierToken} preceded by `@`.
 */
class AtKeywordToken implements RootToken, Component
{
    /**
     * @var         IdentifierToken
     * `IdentifierToken`
     */
    private $_identifier;

    /**
     * @param       IdentifierToken $identifier
     * `IdentifierToken`
     * The {@see IdentifierToken} to become a {@see AtKeywordToken}.
     */
    public function __construct(IdentifierToken $identifier){
        $this->_identifier = $identifier;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return "@" . $this->_identifier;
    }

    /** @inheritDoc */
    public function isParseError(): Bool{
        return FALSE;
    }

    /** @inheritDoc */
    public function newlineCount(): Int{ // @memo
        return $this->_identifier->newlineCount();
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_identifier, $other->_identifier);
    }

    /**
     * Returns the {@see IdentifierToken} for this {@see AtKeywordToken}.
     *
     * @return      IdentifierToken
     * `IdentifierToken`
     * Returns the {@see IdentifierToken} for this {@see AtKeywordToken}.
     */
    public function identifier(): IdentifierToken{
        return $this->_identifier;
    }
}
