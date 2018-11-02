<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\MainNodes;

use Netmosfera\PHPCSSAST\Nodes\Components\Component;
use function Netmosfera\PHPCSSAST\match;

/**
 * An {@see InvalidDeclarationNode} is any sequence of {@see Component} that is not
 * a {@see DeclarationNode}.
 *
 * It is never surrounded by sequences of whitespaces and comments.
 */
class InvalidDeclarationNode
{
    /**
     * @var         Component[]
     * `Array<Int, Component>`
     * @TODOC
     */
    private $_pieces;

    /**
     * @param       Component[] $pieces
     * `Array<Int, Component>`
     * @TODOC
     */
    public function __construct(array $pieces){
        $this->_pieces = $pieces;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return implode("", $this->_pieces);
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_pieces, $this->_pieces);
    }
}
