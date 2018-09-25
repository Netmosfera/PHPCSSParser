<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Token;
use function Netmosfera\PHPCSSAST\match;
use function preg_replace;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class WhitespaceToken implements Token
{
    private $whitespaces;

    private $value;

    function __construct(String $whitespaces){
        $this->whitespaces = $whitespaces;
    }

    function __toString(): String{
        return $this->whitespaces;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->whitespaces, $this->whitespaces);
    }

    function getValue(): String{
        if($this->value === NULL){
            $this->value = preg_replace(
                "/" . SpecData::WHITESPACES_SEQS_SET . "/usD",
                "\n",
                $this->whitespaces
            );
        }
        return $this->value;
    }
}
