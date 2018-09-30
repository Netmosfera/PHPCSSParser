<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Escapes;

/**
 * A {@see ContinuationEscapeToken} is `\` followed by a newline.
 *
 * This has a use in CSS strings; users are allowed to go on multiple lines
 * without affecting the string's value. For example, the following strings have
 * equivalent value:
 *
 * ```
 * div::before{
 *     content: 'hello \
 * world';
 *     content: 'hello world';
 * }
 * ```
 *
 * The code point is always of length `1`, except for `\r\n`, whose actual value
 * (`\n`), however, is also of length `1`.
 */
class ContinuationEscapeToken implements NullEscapeToken
{
    /**
     * @var         String
     * `String`
     */
    private $codePoint;

    /**
     * @param       String                                  $codePoint
     * `String`
     * @TODOC
     */
    public function __construct(String $codePoint){
        $this->codePoint = $codePoint;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return "\\" . $this->codePoint;
    }

    /** @inheritDoc */
    public function getValue(): String{
        return "";
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            $this->codePoint === $other->codePoint;
    }

    /**
     * @TODOC
     *
     * @return      String
     * `String`
     * @TODOC
     */
    public function getCodePoint(): String{
        return $this->codePoint;
    }
}
