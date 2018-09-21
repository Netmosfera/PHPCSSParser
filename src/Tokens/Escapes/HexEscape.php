<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function hexdec;
use function Netmosfera\PHPCSSAST\match;
use IntlChar;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A backslash followed by a unicode code point expressed in hex digits.
 *
 * @TODO add more info
 */
class HexEscape implements ValidEscape
{
    private $hexDigits;

    private $whitespace;

    private $evaluated;

    function __construct(String $hexDigits, String $whitespace){
        $this->hexDigits = $hexDigits;
        $this->whitespace = $whitespace;
    }

    function __toString(): String{
        return "\\" . $this->hexDigits . $this->whitespace;
    }

    function evaluate(): String{
        if($this->evaluated === NULL){
            $this->evaluated = IntlChar::chr(hexdec($this->hexDigits));
        }
        return $this->evaluated;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->hexDigits, $other->hexDigits) &&
            match($this->whitespace, $other->whitespace);
    }

    function getHexDigits(): String{
        return $this->hexDigits;
    }

    function getWhitespace(): String{
        return $this->whitespace;
    }
}
