<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\ComponentValues;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;

/**
 * @TODOC
 */
class FunctionComponentValue implements ComponentValue
{
    /**
     * @var         FunctionToken
     * `FunctionToken`
     * @TODOC
     */
    private $_name;

    /**
     * @var         ComponentValue[]
     * `Array<Int, ComponentValue>`
     * @TODOC
     */
    private $_components;

    /**
     * @var         Bool
     * `Bool`
     * @TODOC
     */
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
        $this->_name = $token;
        $this->_components = $components;
        $this->_EOFTerminated = $EOFTerminated;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return
            (String)$this->_name .
            implode("", $this->_components) .
            ($this->_EOFTerminated ? "" : ")");
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_name, $this->_name) &&
            match($other->_components, $this->_components) &&
            match($other->_EOFTerminated, $this->_EOFTerminated);
    }

    /**
     * Returns the function's name.
     *
     * @return      FunctionToken
     * `FunctionToken`
     * @TODOC
     */
    public function name(): FunctionToken{
        return $this->_name;
    }

    /**
     * @TODOC
     *
     * @return      ComponentValue[]
     * `Array<Int, ComponentValue>`
     * @TODOC
     */
    public function components(): array{
        return $this->_components;
    }

    /**
     * @TODOC
     *
     * @return      Bool
     * `Bool`
     * @TODOC
     */
    public function EOFTerminated(): Bool{
        return $this->_EOFTerminated;
    }
}
