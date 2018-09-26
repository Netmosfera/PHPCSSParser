<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * An {@see EvaluableToken} has an intended value that may not match the literal one.
 *
 * For example, the intended value of the contents of the CSS string `he\6C\6Co \wor\6Cd`
 * equals to `hello world`.
 */
interface EvaluableToken extends Token
{
    /**
     * Returns the intended value of the token.
     *
     * @returns     String
     * `String`
     * @TODOC
     */
    function getValue(): String;
}
