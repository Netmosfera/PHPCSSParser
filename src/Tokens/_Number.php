<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class _Number
{
    public $sign;
    public $wholes;
    public $decimals;
    public $eLetter;
    public $eSign;
    public $eExponent;

    function __construct(
        ?String $sign,
        ?String $wholes,
        ?String $decimals,
        ?String $eLetter,
        ?String $eSign,
        ?String $eExponent
    ){
        assert($wholes !== NULL || $decimals !== NULL);
        assert(($eLetter === NULL) === ($eExponent === NULL));
        assert($eSign === NULL || $eLetter !== NULL);
        $this->sign = $sign;
        $this->wholes = $wholes;
        $this->decimals = $decimals;
        $this->eLetter = $eLetter;
        $this->eSign = $eSign;
        $this->eExponent = $eExponent;
    }

    function hasENotation(): Bool{
        return $this->eLetter !== NULL;
    }

    function getOriginalString(): String{
        $number = (String)$this->wholes;

        if($this->decimals !== NULL){
            $number .= "." . $this->decimals;
        }

        if($this->eLetter !== NULL){
            $number .= $this->eLetter;
            $number .= (String)$this->eSign;
            $number .= $this->eExponent;
        }

        return $number;
    }

    function getNumber(){
        $float = (Float)$this->getOriginalString();
        $floatAsInt = (int)$float;
        $isInt = (float)$floatAsInt === $float;
        return $isInt ? $floatAsInt : $float;
    }

    function getFloat(): Float{
        return (Float)$this->getOriginalString();
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            $other->wholes === $this->wholes &&
            $other->decimals === $this->decimals &&
            $other->eLetter === $this->eLetter &&
            $other->eSign === $this->eSign &&
            $other->eExponent === $this->eExponent;
    }
}
