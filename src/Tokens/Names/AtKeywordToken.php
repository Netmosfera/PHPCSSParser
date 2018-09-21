<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class AtKeywordToken
{
    private $identifier;

    function __construct(IdentifierToken $identifier){
        $this->identifier = $identifier;
    }

    function __toString(): String{
        return "@" . $this->identifier;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->identifier, $other->identifier);
    }

    function getIdentifier(): IdentifierToken{
        return $this->identifier;
    }
}
