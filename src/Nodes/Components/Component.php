<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

/**
 * A {@see Component} is a preserved token (any token except {@see FunctionToken}, `{`,
 * `[` and `(`) or a {@see SimpleBlockComponent} or a {@see FunctionComponent}.
 *
 * This is called "component values" in the CSS-syntax specification.
 */
interface Component
{
    /** @inheritDoc */
    public function __toString(): String;
}
