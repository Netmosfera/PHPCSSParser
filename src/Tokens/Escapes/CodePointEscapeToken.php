<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function hexdec;
use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use IntlChar;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A backslash followed by a unicode code point expressed in hex digits.
 *
 * @TODO add more info
 *
 * @TODO rename this to CodePointEscape
 */
class CodePointEscapeToken implements ValidEscapeToken
{
    /**
     * @var         String                                                                  `String`
     */
    private $hexDigits;

    /**
     * @var         WhitespaceToken|NULL                                                    `WhitespaceToken|NULL`
     */
    private $terminator;

    /**
     * @var         Int|NULL                                                                `Int|NULL`
     */
    private $intValue;

    /**
     * @var         String|NULL                                                             `String|NULL`
     */
    private $value;

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function __construct(String $hexDigits, ?WhitespaceToken $terminator){
        $this->hexDigits = $hexDigits;
        $this->terminator = $terminator;
    }

    /** @inheritDoc */
    function __toString(): String{
        return "\\" . $this->hexDigits . $this->terminator;
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->hexDigits, $other->hexDigits) &&
            match($this->terminator, $other->terminator);
    }

    /** @inheritDoc */
    function getValue(): String{
        if($this->value === NULL){
            $this->value = IntlChar::chr($this->getIntValue()) ?? SpecData::REPLACEMENT_CHARACTER;
        }
        return $this->value;
    }

    /**
     * Tells whether this code point is valid.
     *
     * For example `\FFFFFF` is invalid, as the max code point is `\10FFFF`.
     *
     * When the code point is invalid, {@see self::getValue()} will return the
     * `U+FFFD` replacement character.
     *
     * @returns     Bool                                                                    `Bool`
     * Tells whether this code point is valid.
     */
    function isValidCodePoint(): Bool{
        return $this->getIntValue() <= IntlChar::CODEPOINT_MAX;
    }

    /**
     * Returns the {@see Int} value of the hexadecimal digits.
     *
     * @returns     Int                                                                     `Int`
     * Returns the code point.
     */
    function getIntValue(): Int{
        if($this->intValue === NULL){
            $this->intValue = hexdec($this->hexDigits);
        }
        return $this->intValue;
    }

    /**
     * Returns the hexadecimal digits as they were originally specified.
     *
     * @returns     String                                                                  `String`
     * Returns the hexadecimal digits as they were originally specified.
     */
    function getHexDigits(): String{
        return $this->hexDigits;
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    /**
     * Returns the whitespace terminator of the escape sequence.
     *
     * A single whitespace character is allowed after a code point escape as escape
     * terminator. For example, `\4E E` in CSS equals to `"\u{4E}E"` (with no space in
     * between) in PHP.
     *
     * If any, the returned token has always a computed length of 1.
     *
     * @returns     WhitespaceToken|NULL                                                    `WhitespaceToken|NULL`
     * Returns the whitespace terminator of the escape sequence.
     */
    function getTerminator(): ?WhitespaceToken{
        return $this->terminator;
    }
}
