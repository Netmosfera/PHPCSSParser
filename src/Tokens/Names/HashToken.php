<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Token;

/**
 * A {@see HashToken} is an {@see NameToken} preceded by `#`.
 */
class HashToken implements Token
{
    /**
     * @var         NameToken
     * `NameToken`
     */
    private $name;

    /**
     * @param       NameToken                               $name
     * `NameToken`
     * The {@see NameToken} to become a {@see HashToken}.
     */
    public function __construct(NameToken $name){
        $this->name = $name;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return "#" . $this->name;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->name, $other->name);
    }

    /**
     * Returns the {@see NameToken} for this {@see HashToken}.
     *
     * @return      NameToken
     * `NameToken`
     * Returns the {@see NameToken} for this {@see HashToken}.
     */
    public function getName(): NameToken{
        return $this->name;
    }
}
