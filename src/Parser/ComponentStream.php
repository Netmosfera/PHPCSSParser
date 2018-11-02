<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Netmosfera\PHPCSSAST\Nodes\Components\Component;

class ComponentStream
{
    /**
     * @var         Component[]
     * `Array<Int, Component>`
     * @TODOC
     */
    public $components;

    /**
     * @var         Int
     * `Int`
     * @TODOC
     */
    public $index;

    public function __construct(array $components){
        $this->components = $components;
        $this->index = 0;
    }
}
