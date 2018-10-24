<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names;

use function Netmosfera\PHPCSSAST\match;

/**
 * A {@see FunctionToken} is an {@see IdentifierToken} followed by `(`.
 */
class FunctionToken implements IdentifierLikeToken
{
    /**
     * @var         IdentifierToken
     * `IdentifierToken`
     */
    private $_identifier;

    /**
     * @param       IdentifierToken $identifier
     * `IdentifierToken`
     * The {@see IdentifierToken} to become a {@see FunctionToken}.
     */
    public function __construct(IdentifierToken $identifier){
        $this->_identifier = $identifier;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return $this->_identifier . "(";
    }

    /** @inheritDoc */
    public function newlineCount(): Int{
        return $this->_identifier->newlineCount();
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_identifier, $other->_identifier);
    }

    /**
     * Returns the {@see IdentifierToken} for this {@see FunctionToken}.
     *
     * @return      IdentifierToken
     * `IdentifierToken`
     * Returns the {@see IdentifierToken} for this {@see FunctionToken}.
     */
    public function identifier(): IdentifierToken{
        return $this->_identifier;
    }
}
