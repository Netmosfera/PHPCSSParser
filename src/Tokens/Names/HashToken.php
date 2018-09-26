<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Token;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see HashToken} is an {@see NameToken} preceded by `#`.
 */
class HashToken implements Token
{
    /**
     * @var         NameToken                                                               `NameToken`
     */
    private $name;

    /**
     * @param       NameToken                               $name                           `NameToken`
     * The {@see NameToken} that is to become a {@see HashToken}.
     */
    function __construct(NameToken $name){
        $this->name = $name;
    }

    /** @inheritDoc */
    function __toString(): String{
        return "#" . $this->name;
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->name, $other->name);
    }

    /**
     * Returns the {@see NameToken} for this {@see HashToken}.
     *
     * @returns     NameToken                                                               `NameToken`
     * Returns the {@see NameToken} for this {@see HashToken}.
     */
    function getName(): NameToken{
        return $this->name;
    }
}
