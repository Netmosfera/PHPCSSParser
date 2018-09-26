<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Escape of an encoded code point.
 *
 * `\` followed by any code point usually has a value of that code point; for example,
 * the value of `\x` is `x`. This is useful in strings, as this allows to escape the string
 * delimiter, e.g. `'Bon Jovi - It\'s my life'`.
 */
class EncodedCodePointEscapeToken implements ValidEscapeToken
{
    private $codePoint;

    function __construct(String $codePoint){
        $this->codePoint = $codePoint;
    }

    function __toString(): String{
        return "\\" . $this->codePoint;
    }

    function getValue(): String{
        return $this->codePoint;
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
