<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\SubTokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A backslash followed by a unicode code point in hex digits.
 */
class ActualEscape implements Escape
{
    public $hexDigits;

    public $whitespace;

    function __construct(String $hexDigits, ?String $whitespace){
        $this->hexDigits = $hexDigits;
        $this->whitespace = $whitespace;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->hexDigits, $other->hexDigits) &&
            match($this->whitespace, $other->whitespace);
    }
}
