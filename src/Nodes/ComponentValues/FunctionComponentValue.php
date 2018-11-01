<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\ComponentValues;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;

class FunctionComponentValue implements ComponentValue
{
    private $_token;

    private $_components;

    private $_EOFTerminated;

    /**
     * @param       FunctionToken $token
     * `FunctionToken`
     *
     * @param       ComponentValue[] $components
     * `Array<Int, ComponentValue>`
     *
     * @param       Bool $EOFTerminated
     * `Bool`
     */
    public function __construct(
        FunctionToken $token,
        array $components,
        Bool $EOFTerminated
    ){
        $this->_token = $token;
        $this->_components = $components;
        $this->_EOFTerminated = $EOFTerminated;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return
            (String)$this->_token .
            implode("", $this->_components) .
            ($this->_EOFTerminated ? "" : ")");
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_token, $this->_token) &&
            match($other->_components, $this->_components) &&
            match($other->_EOFTerminated, $this->_EOFTerminated) &&
            TRUE;
    }

    public function token(): FunctionToken{
        return $this->_token;
    }

    public function components(): array{
        return $this->_components;
    }

    public function EOFTerminated(): Bool{
        return $this->_EOFTerminated;
    }
}
