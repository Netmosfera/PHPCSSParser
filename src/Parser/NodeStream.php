<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Netmosfera\PHPCSSAST\Nodes\ComponentValues\ComponentValue;

class NodeStream
{
    /**
     * @var         ComponentValue[]
     * `Array<Int, ComponentValueNode>`
     * @TODOC
     */
    public $nodes;

    /**
     * @var         Int
     * `Int`
     * @TODOC
     */
    public $index;

    public function __construct(array $nodes){
        $this->nodes = $nodes;
        $this->index = 0;
    }
}
