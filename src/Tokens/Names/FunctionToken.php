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
    private $identifier;

    /**
     * @param       IdentifierToken                         $identifier
     * `IdentifierToken`
     * The {@see IdentifierToken} to become a {@see FunctionToken}.
     */
    public function __construct(IdentifierToken $identifier){
        $this->identifier = $identifier;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return $this->identifier . "(";
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->identifier, $other->identifier);
    }

    /**
     * Returns the {@see IdentifierToken} for this {@see FunctionToken}.
     *
     * @return      IdentifierToken
     * `IdentifierToken`
     * Returns the {@see IdentifierToken} for this {@see FunctionToken}.
     */
    public function getIdentifier(): IdentifierToken{
        return $this->identifier;
    }
}
