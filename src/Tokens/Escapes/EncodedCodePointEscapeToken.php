<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see EncodedCodePointEscapeToken} is `\` followed by an encoded code point.
 *
 * With the exception of code points that are hex digits and the newline code points, `\`
 * followed by an encoded code point has a value of that very code point; for example, the
 * value of `\x` is `x`. This is used in strings, for example, as this allows to escape the
 * string delimiter, e.g. `'Bon Jovi - It\'s my life'`.
 */
class EncodedCodePointEscapeToken implements ValidEscapeToken
{
    /**
     * @var         String
     * `String`
     */
    private $codePoint;

    /**
     * @param       String                                  $codePoint
     * `String`
     * @TODOC
     */
    function __construct(String $codePoint){
        $this->codePoint = $codePoint;
    }

    /** @inheritDoc */
    function __toString(): String{
        return "\\" . $this->codePoint;
    }

    /** @inheritDoc */
    function getValue(): String{
        return $this->codePoint;
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            $this->codePoint === $other->codePoint;
    }
}