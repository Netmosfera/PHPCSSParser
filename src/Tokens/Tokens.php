<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens;

use function Netmosfera\PHPCSSAST\match;

/**
 * A list of {@see RootToken}s.
 */
class Tokens
{
    /**
     * @var         RootToken[]
     * `Array<Int, RootToken>`
     */
    private $_tokens;

    /**
     * @param       RootToken[] $tokens
     * `Array<Int, RootToken>`
     * @TODOC
     */
    public function __construct(array $tokens){
        $this->_tokens = $tokens;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return implode("", $this->_tokens);
    }

    /**
     * @TODOC
     *
     * @return      RootToken[]
     * `Array<Int, RootToken>`
     * @TODOC
     */
    public function tokens(): array{
        return $this->_tokens;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_tokens, $other->_tokens);
    }
}
