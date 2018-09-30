<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Token;

/**
 * A {@see AtKeywordToken} is an {@see IdentifierToken} preceded by `@`.
 */
class AtKeywordToken implements Token
{
    /**
     * @var         IdentifierToken
     * `IdentifierToken`
     */
    private $identifier;

    /**
     * @param       IdentifierToken                         $identifier
     * `IdentifierToken`
     * The {@see IdentifierToken} to become a {@see AtKeywordToken}.
     */
    public function __construct(IdentifierToken $identifier){
        $this->identifier = $identifier;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return "@" . $this->identifier;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->identifier, $other->identifier);
    }

    /**
     * Returns the {@see IdentifierToken} for this {@see AtKeywordToken}.
     *
     * @return      IdentifierToken
     * `IdentifierToken`
     * Returns the {@see IdentifierToken} for this {@see AtKeywordToken}.
     */
    public function getIdentifier(): IdentifierToken{
        return $this->identifier;
    }
}
