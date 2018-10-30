<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

use function Netmosfera\PHPCSSAST\match;

class InvalidRuleNode implements RuleNode
{
    private $_pieces;

    private $_stringified;

    public function __construct(array $pieces){
        $this->_pieces = $pieces;
    }

    public function __toString(): String{
        if($this->_stringified === NULL){
            $this->_stringified = implode("", $this->_pieces);
        }
        return $this->_stringified;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_pieces, $this->_pieces) &&
            TRUE;
    }
}
