<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Token;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see AtKeywordToken} is an {@see IdentifierToken} preceded by `@`.
 */
class AtKeywordToken implements Token
{
    /**
     * @var         IdentifierToken                                                         `IdentifierToken`
     */
    private $identifier;

    /**
     * @param       IdentifierToken                         $identifier                     `IdentifierToken`
     * The {@see IdentifierToken} that is to become a {@see AtKeywordToken}.
     */
    function __construct(IdentifierToken $identifier){
        $this->identifier = $identifier;
    }

    /** @inheritDoc */
    function __toString(): String{
        return "@" . $this->identifier;
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->identifier, $other->identifier);
    }

    /**
     * Returns the {@see IdentifierToken} for this {@see AtKeywordToken}.
     *
     * @returns     IdentifierToken                                                         `IdentifierToken`
     * Returns the {@see IdentifierToken} for this {@see AtKeywordToken}.
     */
    function getIdentifier(): IdentifierToken{
        return $this->identifier;
    }
}
