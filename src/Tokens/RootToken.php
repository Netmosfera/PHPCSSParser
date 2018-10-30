<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens;

use Netmosfera\PHPCSSAST\Nodes\ComponentValues\ComponentValue;

/**
 * @TODOC
 */
interface RootToken extends Token, ComponentValue
{
    /**
     * Tells whether the token represents a parse error.
     *
     * @return      Bool
     * `Bool`
     */
    public function isParseError(): Bool;

    // @TODO isParseError() must be is parseErrorInTokenizer()
}
