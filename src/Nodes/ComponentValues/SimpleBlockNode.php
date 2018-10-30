<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\ComponentValues;

use Error;
use function Netmosfera\PHPCSSAST\match;

class SimpleBlockNode implements ComponentValueNode
{
    private $_openDelimiter;

    private $_closeDelimiter;

    private $_components;

    private $_EOFTerminated;

    private $_stringified;

    public function __construct(
        String $openDelimiter,
        array $components,
        Bool $EOFTerminated
    ){
        foreach($components as $component){
            assert($component instanceof ComponentValueNode);
        }
        $this->_openDelimiter = $openDelimiter;
        $this->_components = $components;
        $this->_EOFTerminated = $EOFTerminated;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->_stringified === NULL){
            $this->_stringified = $this->_openDelimiter;
            $this->_stringified .= implode("", $this->_components);
            if($this->_EOFTerminated === FALSE){
                $this->_stringified .= $this->closeDelimiter();
            }
        }
        return $this->_stringified;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_openDelimiter, $this->_openDelimiter) &&
            match($other->_components, $this->_components) &&
            match($other->_EOFTerminated, $this->_EOFTerminated) &&
            TRUE;
    }

    public function openDelimiter(): String{
        return $this->_openDelimiter;
    }

    public function closeDelimiter(): String{
        if($this->_closeDelimiter === NULL){
            if($this->_openDelimiter === "("){
                return $this->_closeDelimiter = ")";
            }elseif($this->_openDelimiter === "["){
                return $this->_closeDelimiter = "]";
            }elseif($this->_openDelimiter === "{"){
                return $this->_closeDelimiter = "}";
            }
            throw new Error("Unknown delimiter");
        }
        return $this->_closeDelimiter;
    }

    public function components(): array{
        return $this->_components;
    }

    public function EOFTerminated(): Bool{
        return $this->_EOFTerminated;
    }
}
