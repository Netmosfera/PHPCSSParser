<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\ComponentValues;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;

class FunctionNode implements ComponentValueNode
{
    private $_token;

    private $_components;

    private $_EOFTerminated;

    private $_stringified;

    public function __construct(
        FunctionToken $token,
        array $components,
        Bool $EOFTerminated
    ){
        foreach($components as $component){
            assert($component instanceof ComponentValueNode);
        }
        $this->_token = $token;
        $this->_components = $components; // @TODO ComponentValueNode
        $this->_EOFTerminated = $EOFTerminated;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->_stringified === NULL){
            $this->_stringified = (String)$this->_token;
            $this->_stringified .= implode("", $this->_components);
            $this->_stringified .= $this->_EOFTerminated ? "" : ")";
        }
        return $this->_stringified;
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
