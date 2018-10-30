<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\ComponentValues;

use function Netmosfera\PHPCSSAST\match;

class Nodes
{
    /**
     * @var         ComponentValueNode[]
     * `Array<Int, ComponentValueNode>`
     */
    private $_nodes;

    /**
     * @param       ComponentValueNode[] $nodes
     * `Array<Int, ComponentValueNode>`
     * @TODOC
     */
    public function __construct(array $nodes){
        foreach($nodes as $node){
            assert($node instanceof ComponentValueNode);
        }
        $this->_nodes = $nodes;

        // @TODO this must disallow the presence of { [ and ( tokens as those
        // must appear as simple blocks

        // also checks that there are no identifiers followed by a () simple block
        // because those must appear as functionnodes
    }

    /** @inheritDoc */
    public function __toString(): String{
        return implode("", $this->_nodes); // @TODO memoize
    }

    /**
     * @TODOC
     *
     * @return      ComponentValueNode[]
     * `Array<Int, ComponentValueNode>`
     * @TODOC
     */
    public function nodes(): array{
        return $this->_nodes;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_nodes, $other->_nodes);
    }
}
