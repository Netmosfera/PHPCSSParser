<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Escapes;

use function hexdec;
use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\SpecData;
use IntlChar;

/**
 * A {@see CodePointEscapeToken} is `\` followed by one to six hex digits.
 */
class CodePointEscapeToken implements ValidEscapeToken
{
    /**
     * @var         String
     * `String`
     */
    private $_hexDigits;

    /**
     * @var         WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     */
    private $_terminator;

    /**
     * @var         Int|NULL
     * `Int|NULL`
     */
    private $_intValue;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $_intendedValue;

    /**
     * @param       String                                  $hexDigits
     * `String`
     * @TODOC
     *
     * @param       WhitespaceToken|NULL                    $terminator
     * `WhitespaceToken|NULL`
     * @TODOC
     */
    public function __construct(
        String $hexDigits,
        ?WhitespaceToken $terminator
    ){
        $this->_hexDigits = $hexDigits;
        $this->_terminator = $terminator;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return "\\" . $this->_hexDigits . $this->_terminator;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_hexDigits, $other->_hexDigits) &&
            match($this->_terminator, $other->_terminator);
    }

    /** @inheritDoc */
    public function intendedValue(): String{
        if($this->_intendedValue === NULL){
            $this->_intendedValue = IntlChar::chr(
                $this->integerValue()
            ) ?? SpecData::REPLACEMENT_CHARACTER;
        }
        return $this->_intendedValue;
    }

    /**
     * Tells whether this code point is valid.
     *
     * For example `\FFFFFF` is invalid, as the max code point is `\10FFFF`.
     *
     * When the code point is invalid, {@see self::getValue()} will return the
     * `U+FFFD` replacement character.
     *
     * @return      Bool
     * `Bool`
     * Tells whether this code point is valid.
     */
    public function isValid(): Bool{
        return $this->integerValue() <= IntlChar::CODEPOINT_MAX;
    }

    /**
     * Returns the {@see Int} value of the hexadecimal digits.
     *
     * @return      Int
     * `Int`
     * Returns the code point.
     */
    public function integerValue(): Int{
        if($this->_intValue === NULL){
            $this->_intValue = hexdec($this->_hexDigits);
        }
        return $this->_intValue;
    }

    /**
     * Returns the hexadecimal digits as they were originally specified.
     *
     * @return      String
     * `String`
     * Returns the hexadecimal digits as they were originally specified.
     */
    public function hexDigits(): String{
        return $this->_hexDigits;
    }

    /**
     * Returns the whitespace terminator of the escape sequence.
     *
     * A single whitespace character is allowed after a code point escape as
     * escape terminator. For example, `\4E E` in CSS equals to `"\u{4E}E"`
     * (with no space in between the two) in PHP.
     *
     * If any, the returned token has always a computed length of `1`.
     *
     * @return      WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     * Returns the whitespace terminator of the escape sequence.
     */
    public function terminator(): ?WhitespaceToken{
        return $this->_terminator;
    }
}
