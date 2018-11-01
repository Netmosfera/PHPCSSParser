<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\CurlySimpleBlockComponentValue;

class QualifiedRuleNode implements RuleNode
{
    private $_preludePieces;

    private $_terminator;

    public function __construct(
        array $preludePieces,
        CurlySimpleBlockComponentValue $terminator
    ){
        $this->_preludePieces = $preludePieces;
        $this->_terminator = $terminator;
    }

    public function __toString(): String{
        return implode("", $this->_preludePieces) . $this->_terminator;
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
