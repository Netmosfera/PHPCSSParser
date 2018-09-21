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

    function __construct(
        String $sign,
        String $wholes,
        String $decimals,
        String $ELetter,
        String $ESign,
        String $EExponent
    ){
        $this->sign = $sign;

        assert($wholes !== "" || $decimals !== "");
        $this->wholes = $wholes;
        $this->decimals = $decimals;

        assert(($ELetter === "") === ($EExponent === ""));
        assert($ESign === "" || $ELetter !== "");
        $this->ELetter = $ELetter;
        $this->ESign = $ESign;
        $this->EExponent = $EExponent;
    }

    function __toString(): String{
        $number = $this->wholes;

        if($this->decimals !== ""){
            $number .= "." . $this->decimals;
        }

        $number .= $this->ELetter;
        $number .= $this->ESign;
        $number .= $this->EExponent;

        return $number;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->sign, $this->sign) &&
            match($other->wholes, $this->wholes) &&
            match($other->decimals, $this->decimals) &&
            match($other->ELetter, $this->ELetter) &&
            match($other->ESign, $this->ESign) &&
            match($other->EExponent, $this->EExponent);
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
        return (Float)(String)$this;
    }

    function toNumber(){
        $float = (Float)(String)$this;
        $floatAsInt = (int)$float;
        $isInt = (float)$floatAsInt === $float;
        return $isInt ? $floatAsInt : $float;
    }
}
