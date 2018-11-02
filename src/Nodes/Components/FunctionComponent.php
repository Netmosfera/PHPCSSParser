<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use function Netmosfera\PHPCSSAST\match;

/**
 * @TODOC
 */
class FunctionComponent implements Component
{
    /**
     * @var         FunctionToken
     * `FunctionToken`
     * @TODOC
     */
    private $_name;

    /**
     * @var         Component[]
     * `Array<Int, Component>`
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
     * @param       Component[] $components
     * `Array<Int, Component>`
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
     * @return      Component[]
     * `Array<Int, Component>`
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
