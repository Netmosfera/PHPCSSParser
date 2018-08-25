<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\SubTokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A backslash followed by a unicode code point that is a sequence of hex digits.
 *
 * PlainEscape::$codePoint is always of length 1, except for "\r\n".
 */
class PlainEscape implements Escape
{
    public $codePoint;

    function __construct(String $codePoint){
        $this->codePoint = $codePoint;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            $this->codePoint === $other->codePoint;
    }
}
