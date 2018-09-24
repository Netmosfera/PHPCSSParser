<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A continuation is a backslash followed by a newline.
 *
 * This has a use in CSS strings; users are allowed to go on multiple lines without
 * affecting the string's value. For example, the following strings have equivalent value:
 *
 * ```
 * div::before{
 *     content: 'hello \
 * world';
 *     content: 'hello world';
 * }
 * ```
 *
 * The code point is always of length `1`, except for `\r\n`, whose actual value, however,
 * is also of length `1` (`\n`, as per-spec).
 */
class ContinuationEscape implements NullEscape
{
    private $codePoint;

    function __construct(String $codePoint){
        $this->codePoint = $codePoint;
    }

    function __toString(): String{
        return "\\" . $this->codePoint;
    }

    function getValue(): String{
        return "";
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            $this->codePoint === $other->codePoint;
    }

    function getCodePoint(): String{
        return $this->codePoint;
    }
}
