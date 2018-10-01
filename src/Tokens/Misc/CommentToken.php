<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Token;

/**
 * A {@see CommentToken} is a programmer-readable explanation or annotation.
 *
 * It can contain any text, except the character `*` followed by `/`.
 */
class CommentToken implements Token
{
    /**
     * @var         String
     * `String`
     */
    private $_text;

    /**
     * @var         Bool
     * `Bool`
     */
    private $_precedesEOF;

    /**
     * @var         String
     * `String`
     */
    private $_stringValue;

    /**
     * @param       String                                  $text
     * `String`
     * The comment's text.
     *
     * @param       Bool                                    $precedesEOF
     * `Bool`
     * Whether the comment is unterminated.
     */
    public function __construct(String $text, Bool $precedesEOF){
        $this->_text = $text;
        $this->_precedesEOF = $precedesEOF;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->_stringValue === NULL){
            $this->_stringValue = "/*" . $this->_text;
            $this->_stringValue .= $this->_precedesEOF ? "" : "*/";
        }
        return $this->_stringValue;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_text, $this->_text) &&
            match($other->_precedesEOF, $this->_precedesEOF);
    }

    /**
     * Returns the comment's text.
     *
     * @return      String
     * `String`
     * Returns the comment's text.
     */
    public function text(): String{
        return $this->_text;
    }

    /**
     * Returns {@see TRUE} if the comment is unterminated.
     *
     * @return      Bool
     * `Bool`
     * Returns {@see TRUE} if the comment is unterminated.
     */
    public function precedesEOF(): Bool{
        return $this->_precedesEOF;
    }
}
