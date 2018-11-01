<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\ComponentValue;

/**
 * Any sequence of {@see ComponentValue}s that is not a {@see QualifiedRuleNode} or a
 * {@see AtRuleNode}.
 *
 * It is never surrounded by sequences of whitespaces and comments.
 */
class InvalidRuleNode
{
    /**
     * @var         ComponentValue[]
     * `Array<Int, ComponentValue>`
     * @TODOC
     */
    private $_pieces;

    /**
     * @param       ComponentValue[] $pieces
     * `Array<Int, ComponentValue>`
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
