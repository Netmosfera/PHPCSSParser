<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data;

use Error;
use IntlChar;
use function dechex;

class CodePoint
{
    private $code;

    public function __construct(Int $code){
        if($code < 0 || $code > IntlChar::CODEPOINT_MAX){
            throw new Error("Invalid code point");
        }
        $this->code = $code;
    }

    public function getCode(): Int{
        return $this->code;
    }

    public function getHexCode(): String{
        return dechex($this->code);
    }

    public function getString(): String{
        return IntlChar::chr($this->code);
    }
}
