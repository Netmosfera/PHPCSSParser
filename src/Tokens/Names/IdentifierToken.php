<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class IdentifierToken implements IdentifierLikeToken
{
    private $name;

    function __construct(NameToken $name){
        $this->name = $name;
    }

    function __toString(): String{
        return (String)$this->name;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->name, $other->name);
    }

    function getName(): NameToken{
        return $this->name;
    }
}
