<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes;

use function Netmosfera\PHPCSSAST\match;

class QualifiedRuleNode implements RuleNode
{
    private $_preludePieces;

    private $_terminator;

    private $_stringified;

    public function __construct(
        array $preludePieces,
        $terminator
    ){
        assert(
            $terminator instanceof SimpleBlockNode ||
            $terminator === NULL     // EOF terminated
        );
        $this->_preludePieces = $preludePieces;
        $this->_terminator = $terminator;
    }


    public function __toString(): String{
        if($this->_stringified === NULL){
            $this->_stringified .= implode("", $this->_preludePieces);
            $this->_stringified .= $this->_terminator;
        }
        return $this->_stringified;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_preludePieces, $this->_preludePieces) &&
            match($other->_terminator, $this->_terminator) &&
            TRUE;
    }
}
