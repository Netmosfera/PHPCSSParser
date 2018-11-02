<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Nodes\Components\Component;
use Netmosfera\PHPCSSAST\Tokens\RootToken;

/**
 * A {@see HashToken} is an {@see NameToken} preceded by `#`.
 */
class HashToken implements RootToken, Component
{
    /**
     * @var         NameToken
     * `NameToken`
     */
    private $_name;

    /**
     * @param       NameToken $name
     * `NameToken`
     * The {@see NameToken} to become a {@see HashToken}.
     */
    public function __construct(NameToken $name){
        $this->_name = $name;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return "#" . $this->_name;
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
     * Returns the {@see NameToken} for this {@see HashToken}.
     *
     * @return      NameToken
     * `NameToken`
     * Returns the {@see NameToken} for this {@see HashToken}.
     */
    public function name(): NameToken{
        return $this->_name;
    }
}
