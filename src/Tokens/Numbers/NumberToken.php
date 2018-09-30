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
    private $sign;

    /**
     * @var         String
     * `String`
     */
    private $wholes;

    /**
     * @var         String
     * `String`
     */
    private $decimals;

    /**
     * @var         String
     * `String`
     */
    private $EIndicator;

    /**
     * @var         String
     * `String`
     */
    private $ESign;

    /**
     * @var         String
     * `String`
     */
    private $EExponent;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $stringified;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $floatified;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $numberified;

    /**
     * @param       String                                  $sign
     * `String`
     * The number's sign; it is `"+"`, `"-"` or `""`.
     *
     * @param       String                                  $wholes
     * `String`
     * The number's whole part; it is any sequence of digits or `""`.
     *
     * @param       String                                  $decimals
     * `String`
     * The number's decimal part; it is any sequence of digits or `""`.
     *
     * @param       String                                  $EIndicator
     * `String`
     * The "E" indicator; it is `"E"`, `"e"` or `""`.
     *
     * @param       String                                  $ESign
     * `String`
     * The E-notation's exponent's sign; it is `"+"`, `"-"` or `""`.
     *
     * @param       String                                  $EExponent
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
        $this->sign = $sign;
        $this->wholes = $wholes;
        $this->decimals = $decimals;
        $this->EIndicator = $EIndicator;
        $this->ESign = $ESign;
        $this->EExponent = $EExponent;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->stringified === NULL){
            $number  = $this->sign;
            $number .= $this->wholes;
            $number .= $this->decimals === "" ? "" : "." . $this->decimals;
            $number .= $this->EIndicator;
            $number .= $this->ESign;
            $number .= $this->EExponent;
            $this->stringified = $number;
        }
        return $this->stringified;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->sign, $this->sign) &&
            match($other->ESign, $this->ESign) &&
            match($other->EIndicator, $this->EIndicator) &&
            match($other->wholes, $this->wholes) &&
            match($other->decimals, $this->decimals) &&
            match($other->EExponent, $this->EExponent) &&
            TRUE;
    }

    /**
     * Returns the number's sign; it is `"+"`, `"-"` or `""`.
     *
     * @return      String
     * `String`
     * Returns the number's sign; it is `"+"`, `"-"` or `""`.
     */
    public function getSign(): String{
        return $this->sign;
    }

    /**
     * Returns the number's whole part; it is any sequence of digits or `""`.
     *
     * @return      String
     * `String`
     * Returns the number's whole part; it is any sequence of digits or `""`.
     */
    public function getWholes(): String{
        return $this->wholes;
    }

    /**
     * Returns the number's decimal part; it is any sequence of digits or `""`.
     *
     * @return      String
     * `String`
     * Returns the number's decimal part; it is any sequence of digits or `""`.
     */
    public function getDecimals(): String{
        return $this->decimals;
    }

    /**
     * Returns the "E" indicator; it is `"E"`, `"e"` or `""`.
     *
     * @return      String
     * `String`
     * Returns the "E" indicator; it is `"E"`, `"e"` or `""`.
     */
    public function getEIndicator(): String{
        return $this->EIndicator;
    }

    /**
     * Returns the E-notation's exponent's sign; it is `"+"`, `"-"` or `""`.
     *
     * @return      String
     * `String`
     * Returns the E-notation's exponent's sign; it is `"+"`, `"-"` or `""`.
     */
    public function getESign(): string{
        return $this->ESign;
    }

    /**
     * Returns the E-notation's exponent; it is any sequence of digits or `""`.
     *
     * @return      String
     * `String`
     * Returns the E-notation's exponent; it is any sequence of digits or `""`.
     */
    public function getEExponent(): String{
        return $this->EExponent;
    }

    /**
     * Returns the number as {@see Float}
     *
     * @return      Float
     * `Float`
     * Returns the number as {@see Float}
     */
    public function toFloat(): Float{
        if($this->floatified === NULL){
            $this->floatified = (Float)(String)$this;
        }
        return $this->floatified;
    }

    /**
     * Returns the number as {@see Int} if possible, otherwise {@see Float}.
     *
     * @return      Int|Float
     * `Int|Float`
     * Returns the number as {@see Int} if possible, otherwise {@see Float}.
     */
    public function toNumber(){
        if($this->numberified === NULL){
            $float = $this->toFloat();
            $floatAsInt = (int)$float;
            $isInt = (float)$floatAsInt === $float;
            $this->numberified = $isInt ? $floatAsInt : $float;
        }
        return $this->numberified;
    }
}
