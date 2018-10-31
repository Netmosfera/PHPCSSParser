<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens;

/**
 * @TODOC
 */
interface RootToken extends Token
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
