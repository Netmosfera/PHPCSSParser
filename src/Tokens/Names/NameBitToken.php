<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;
use Netmosfera\PHPCSSAST\SpecData;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class NameBitToken implements EvaluableToken
{
    private $text;

    private $value;

    function __construct(String $text){
        $this->text = $text;
    }

    function __toString(): String{
        return $this->text;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->text, $this->text);
    }

    function getText(): String{
        return $this->text;
    }

    /** @inheritDoc */
    function getValue(): String{
        if($this->value === NULL){
            $this->value = str_replace("\0", SpecData::REPLACEMENT_CHARACTER, $this->text);
        }
        return $this->value;
    }
}
