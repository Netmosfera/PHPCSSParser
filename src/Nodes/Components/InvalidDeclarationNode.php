<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

use function Netmosfera\PHPCSSAST\match;

class InvalidDeclarationNode
{
    private $_pieces;

    public function __construct(array $pieces){
        $this->_pieces = $pieces;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return implode("", $this->_pieces);
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_pieces, $this->_pieces) &&
            TRUE;
    }
}
