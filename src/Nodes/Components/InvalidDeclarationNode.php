<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\ComponentValue;

class InvalidDeclarationNode
{
    private $_pieces;

    private $_stringValue;

    public function __construct(array $pieces){
        $this->_pieces = $pieces;

        foreach($pieces as $definitionPiece){
            assert($definitionPiece instanceof ComponentValue);
        }
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->_stringValue === NULL){
            $this->_stringValue = implode("", $this->_pieces);
        }
        return $this->_stringValue;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_pieces, $this->_pieces) &&
            TRUE;
    }
}
