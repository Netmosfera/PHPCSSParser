<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

/**
 * @TODOC
 */
interface SimpleBlockComponent extends Component
{
    /**
     * @TODOC
     *
     * @return      Component[]
     * `Array<Int, Component>`
     * @TODOC
     */
    public function components(): array;

    /**
     * @TODOC
     *
     * @return      Bool
     * `Bool`
     * @TODOC
     */
    public function EOFTerminated(): Bool;
}
