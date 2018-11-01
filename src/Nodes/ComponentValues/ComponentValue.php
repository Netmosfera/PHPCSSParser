<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\ComponentValues;

/**
 * A {@see ComponentValue} is a preserved token (any token except {@see FunctionToken},
 * `{`, `[` and `(`) or a {@see SimpleBlockComponentValue} or a
 * {@see FunctionComponentValue}.
 */
interface ComponentValue
{
    /** @inheritDoc */
    public function __toString(): String;
}
