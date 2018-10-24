<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Escapes;

/**
 * A {@see ContinuationEscapeToken} is `\` followed by a newline.
 *
 * This has a use in CSS strings; users are allowed to go on multiple lines  without
 * affecting the string's value. For example, the following strings have equivalent value:
 *
 * ```
 * div::before{
 *     content: 'hello \
 * world';
 *     content: 'hello world';
 * }
 * ```
 *
 * The newline is of length `1` unless it's `\r\n`, whose actual computed value value in
 * CSS (`\n`), however, is also of length `1`.
 */
class ContinuationEscapeToken implements NullEscapeToken
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
        return 1;
    }

    /** @inheritDoc */
    public function intendedValue(): String{
        return "";
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            $this->_codePoint === $other->_codePoint;
    }

    /**
     * @TODOC
     *
     * @return      String
     * `String`
     * @TODOC
     */
    public function codePoint(): String{
        return $this->_codePoint;
    }
}
