<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use IntlChar;
use function dechex;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CodePoint
{
    private $code;

    function __construct(Int $code){
        if($code < 0 || $code > IntlChar::CODEPOINT_MAX){
            throw new Error("Invalid code point");
        }
        $this->code = $code;
    }

    function getCode(): Int{
        return $this->code;
    }

    function getHexCode(): String{
        return dechex($this->code);
    }

    function getString(): String{
        return IntlChar::chr($this->code);
    }
}
