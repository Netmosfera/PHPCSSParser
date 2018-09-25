<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class NumberToken implements NumericToken
{
    private $sign;

    private $wholes;

    private $decimals;

    private $ELetter;

    private $ESign;

    private $EExponent;

    private $stringified;

    private $floatified;

    private $numberified;

    function __construct(
        String $sign,
        String $wholes,
        String $decimals,
        String $ELetter,
        String $ESign,
        String $EExponent
    ){
        $this->sign = $sign;
        $this->wholes = $wholes;
        $this->decimals = $decimals;
        $this->ELetter = $ELetter;
        $this->ESign = $ESign;
        $this->EExponent = $EExponent;
    }

    function __toString(): String{
        if($this->stringified === NULL){
            $number  = $this->sign;
            $number .= $this->wholes;
            $number .= $this->decimals === "" ? "" : "." . $this->decimals;
            $number .= $this->ELetter;
            $number .= $this->ESign;
            $number .= $this->EExponent;
            $this->stringified = $number;
        }
        return $this->stringified;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->sign, $this->sign) &&
            match($other->ESign, $this->ESign) &&
            match($other->ELetter, $this->ELetter) &&
            match($other->wholes, $this->wholes) &&
            match($other->decimals, $this->decimals) &&
            match($other->EExponent, $this->EExponent) &&
            TRUE;
    }

    function getSign(): String{
        return $this->sign;
    }

    function getWholes(): String{
        return $this->wholes;
    }

    function getDecimals(): String{
        return $this->decimals;
    }

    function getELetter(): String{
        return $this->ELetter;
    }

    function getESign(): String{
        return $this->ESign;
    }

    function getEExponent(): String{
        return $this->EExponent;
    }

    function toFloat(): Float{
        if($this->floatified === NULL){
            $this->floatified = (Float)(String)$this;
        }
        return $this->floatified;
    }

    function toNumber(){
        if($this->numberified === NULL){
            $float = $this->toFloat();
            $floatAsInt = (int)$float;
            $isInt = (float)$floatAsInt === $float;
            $this->numberified = $isInt ? $floatAsInt : $float;
        }
        return $this->numberified;
    }
}
