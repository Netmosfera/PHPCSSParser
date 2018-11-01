<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\ComponentValues;

use function Netmosfera\PHPCSSAST\match;

class ParenthesesSimpleBlockComponentValue implements SimpleBlockComponentValue
{
    private $_components;

    private $_EOFTerminated;

    public function __construct(array $components, Bool $EOFTerminated){
        $this->_components = $components;
        $this->_EOFTerminated = $EOFTerminated;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return
            "(" . implode("", $this->_components) .
            ($this->_EOFTerminated ? "" : ")");
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_components, $this->_components) &&
            match($other->_EOFTerminated, $this->_EOFTerminated) &&
            TRUE;
    }

    public function openDelimiter(): String{
        return "(";
    }

    public function closeDelimiter(): String{
        return ")";
    }

    public function components(): array{
        return $this->_components;
    }

    public function EOFTerminated(): Bool{
        return $this->_EOFTerminated;
    }
}
