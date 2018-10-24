<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Escapes;

use Netmosfera\PHPCSSAST\SpecData;

/**
 * A {@see EncodedCPEscapeToken} is `\` followed by an encoded code point.
 *
 * With the exception of code points that are hex digits and the newline code  points, `\`
 * followed by an encoded code point has a value of that very code point; for example, the
 * value of `\x` is `x`. This is used in strings, for example, as this allows to escape
 * the string delimiter, e.g. `'Bon Jovi - It\'s my life'`.
 */
class EncodedCodePointEscapeToken implements ValidEscapeToken
{
    /**
     * @var         String
     * `String`
     */
    private $_codePoint;

    /**
     * @param       String $codePoint
     * `String`
     * @TODOC
     */
    public function __construct(String $codePoint){
        $this->_codePoint = $codePoint;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return "\\" . $this->_codePoint;
    }

    /** @inheritDoc */
    public function newlineCount(): Int{
        return 0;
    }

    /** @inheritDoc */
    public function intendedValue(): String{
        return $this->_codePoint === "\0" ?
            SpecData::REPLACEMENT_CHARACTER :
            $this->_codePoint;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            $this->_codePoint === $other->_codePoint;
    }
}
