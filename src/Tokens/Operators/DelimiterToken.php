<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Operators;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Token;

/**
 * A {@see DelimiterToken} represents a generic single-character delimiter.
 *
 * Common occurrences of this class in CSS are the `.`, the prefix of class
 * names, the math operators like `*` `+` `/`, etc.
 *
 * N.B. a delimiter token of value `\` represents a parse-error.
 */
class DelimiterToken implements Token
{
    /**
     * @var         String
     * `String`
     */
    private $_delimiter;

    /**
     * @param       String                                  $delimiter
     * `String`
     * The delimiter code point.
     */
    public function __construct(String $delimiter){
        $this->_delimiter = $delimiter;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return $this->_delimiter;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_delimiter, $this->_delimiter);
    }
}
