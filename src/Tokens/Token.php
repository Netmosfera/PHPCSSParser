<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens;

/**
 * @TODOC
 */
interface Token
{
    /** @inheritDoc */
    public function __toString(): String;

    /**
     * Returns the count of newlines appearing in this token.
     *
     * @returns     Int
     * `Int`
     * Returns the count of newlines appearing in this token.
     */
    public function newlineCount(): Int;
}
