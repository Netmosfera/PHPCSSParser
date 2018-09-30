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
    private $text;

    /**
     * @var         Bool
     * `Bool`
     */
    private $terminatedWithEOF;

    /**
     * @var         String
     * `String`
     */
    private $stringified;

    /**
     * @param       String                                  $text
     * `String`
     * The comment's text.
     *
     * @param       Bool                                    $terminatedWithEOF
     * `Bool`
     * Whether the comment is unterminated.
     */
    public function __construct(String $text, Bool $terminatedWithEOF){
        $this->text = $text;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->stringified === NULL){
            $this->stringified = "/*" . $this->text;
            $this->stringified .= $this->terminatedWithEOF ? "" : "*/";
        }
        return $this->stringified;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->text, $this->text) &&
            match($other->terminatedWithEOF, $this->terminatedWithEOF);
    }

    /**
     * Returns the comment's text.
     *
     * @return      String
     * `String`
     * Returns the comment's text.
     */
    public function getText(): String{
        return $this->text;
    }

    /**
     * Returns {@see TRUE} if the comment is unterminated.
     *
     * @return      Bool
     * `Bool`
     * Returns {@see TRUE} if the comment is unterminated.
     */
    public function isTerminatedWithEOF(): Bool{
        return $this->terminatedWithEOF;
    }
}
