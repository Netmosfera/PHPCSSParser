<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

use function Netmosfera\PHPCSSAST\match;

/**
 * A {@see SimpleBlockComponent} of curly brackets.
 */
class CurlySimpleBlockComponent implements SimpleBlockComponent
{
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
     * @param       Component[] $components
     * `Array<Int, Component>`
     * @TODOC
     *
     * @param       Bool $EOFTerminated
     * `Bool`
     * @TODOC
     */
    public function __construct(array $components, Bool $EOFTerminated){
        $this->_components = $components;
        $this->_EOFTerminated = $EOFTerminated;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return
            "{" . implode("", $this->_components) .
            ($this->_EOFTerminated ? "" : "}");
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_components, $this->_components) &&
            match($other->_EOFTerminated, $this->_EOFTerminated);
    }

    /** @inheritDoc */
    public function components(): array{
        return $this->_components;
    }

    /** @inheritDoc */
    public function EOFTerminated(): Bool{
        return $this->_EOFTerminated;
    }
}
