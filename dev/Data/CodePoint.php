<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data;

use Error;
use IntlChar;
use function dechex;

class CodePoint
{
    private $_code;

    public function __construct(Int $code){
        if($code < 0 || $code > IntlChar::CODEPOINT_MAX){
            throw new Error("Invalid code point");
        }
        $this->_code = $code;
    }

    public function __toString(): String{
        return IntlChar::chr($this->_code);
    }

    public function regexp(): String{
        if((String)$this === "\0"){
            return '\\x{' . dechex($this->_code) . '}';
        }else{
            return preg_quote((String)$this, "/");
        }
    }

    public function code(): Int{
        return $this->_code;
    }
}
