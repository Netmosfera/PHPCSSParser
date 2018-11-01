<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\ComponentValues;

/**
 * @TODOC
 */
interface SimpleBlockComponentValue extends ComponentValue
{
    /**
     * @TODOC
     *
     * @return      ComponentValue[]
     * `Array<Int, ComponentValue>`
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
