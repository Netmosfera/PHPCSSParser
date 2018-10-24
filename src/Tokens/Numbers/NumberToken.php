<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Numbers;

use function Netmosfera\PHPCSSAST\match;

/**
 * A {@see NumberToken} is a integral or decimal number.
 */
class NumberToken implements NumericToken
{
    /**
     * @var         String
     * `String`
     */
    private $_sign;

    /**
     * @var         String
     * `String`
     */
    private $_wholes;

    /**
     * @var         String
     * `String`
     */
    private $_decimals;

    /**
     * @var         String
     * `String`
     */
    private $_EIndicator;

    /**
     * @var         String
     * `String`
     */
    private $_ESign;

    /**
     * @var         String
     * `String`
     */
    private $_EExponent;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $_stringValue;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $_floatValue;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $_numberValue;

    /**
     * @param       String $sign
     * `String`
     * The number's sign; it is `"+"`, `"-"` or `""`.
     *
     * @param       String $wholes
     * `String`
     * The number's whole part; it is any sequence of digits or `""`.
     *
     * @param       String $decimals
     * `String`
     * The number's decimal part; it is any sequence of digits or `""`.
     *
     * @param       String $EIndicator
     * `String`
     * The "E" indicator; it is `"E"`, `"e"` or `""`.
     *
     * @param       String $ESign
     * `String`
     * The E-notation's exponent's sign; it is `"+"`, `"-"` or `""`.
     *
     * @param       String $EExponent
     * `String`
     * The E-notation's exponent; it is any sequence of digits or `""`.
     */
    public function __construct(
        String $sign,
        String $wholes,
        String $decimals,
        String $EIndicator,
        String $ESign,
        String $EExponent
    ){
        $this->_sign = $sign;
        $this->_wholes = $wholes;
        $this->_decimals = $decimals;
        $this->_EIndicator = $EIndicator;
        $this->_ESign = $ESign;
        $this->_EExponent = $EExponent;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->_stringValue === NULL){
            $number  = $this->_sign;
            $number .= $this->_wholes;
            $number .= $this->_decimals === "" ? "" : "." . $this->_decimals;
            $number .= $this->_EIndicator;
            $number .= $this->_ESign;
            $number .= $this->_EExponent;
            $this->_stringValue = $number;
        }
        return $this->_stringValue;
    }

    /** @inheritDoc */
    public function newlineCount(): Int{
        return 0;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_sign, $this->_sign) &&
            match($other->_ESign, $this->_ESign) &&
            match($other->_EIndicator, $this->_EIndicator) &&
            match($other->_wholes, $this->_wholes) &&
            match($other->_decimals, $this->_decimals) &&
            match($other->_EExponent, $this->_EExponent) &&
            TRUE;
    }

    /**
     * Returns the number's sign; it is `"+"`, `"-"` or `""`.
     *
     * @return      String
     * `String`
     * Returns the number's sign; it is `"+"`, `"-"` or `""`.
     */
    public function sign(): String{
        return $this->_sign;
    }

    /**
     * Returns the number's whole part; it is any sequence of digits or `""`.
     *
     * @return      String
     * `String`
     * Returns the number's whole part; it is any sequence of digits or `""`.
     */
    public function wholes(): String{
        return $this->_wholes;
    }

    /**
     * Returns the number's decimal part; it is any sequence of digits or `""`.
     *
     * @return      String
     * `String`
     * Returns the number's decimal part; it is any sequence of digits or `""`.
     */
    public function decimals(): String{
        return $this->_decimals;
    }

    /**
     * Returns the "E" indicator; it is `"E"`, `"e"` or `""`.
     *
     * @return      String
     * `String`
     * Returns the "E" indicator; it is `"E"`, `"e"` or `""`.
     */
    public function EIndicator(): String{
        return $this->_EIndicator;
    }

    /**
     * Returns the E-notation's exponent's sign; it is `"+"`, `"-"` or `""`.
     *
     * @return      String
     * `String`
     * Returns the E-notation's exponent's sign; it is `"+"`, `"-"` or `""`.
     */
    public function ESign(): string{
        return $this->_ESign;
    }

    /**
     * Returns the E-notation's exponent; it is any sequence of digits or `""`.
     *
     * @return      String
     * `String`
     * Returns the E-notation's exponent; it is any sequence of digits or `""`.
     */
    public function EExponent(): String{
        return $this->_EExponent;
    }

    /**
     * Returns the number as {@see Float}
     *
     * @return      Float
     * `Float`
     * Returns the number as {@see Float}
     */
    public function floatValue(): Float{
        if($this->_floatValue === NULL){
            $this->_floatValue = (Float)(String)$this;
        }
        return $this->_floatValue;
    }

    /**
     * Returns the number as {@see Int} if possible, otherwise {@see Float}.
     *
     * @return      Int|Float
     * `Int|Float`
     * Returns the number as {@see Int} if possible, otherwise {@see Float}.
     */
    public function numberValue(){
        if($this->_numberValue === NULL){
            $float = $this->floatValue();
            $floatAsInt = (int)$float;
            $isInt = (float)$floatAsInt === $float;
            $this->_numberValue = $isInt ? $floatAsInt : $float;
        }
        return $this->_numberValue;
    }
}
