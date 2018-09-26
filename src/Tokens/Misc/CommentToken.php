<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Token;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see CommentToken} is a programmer-readable explanation or annotation.
 *
 * It can contain any text, except the character `*` followed by `/`.
 */
class CommentToken implements Token
{
    /**
     * @var         String                                                                  `String`
     */
    private $text;

    /**
     * @var         Bool                                                                    `Bool`
     */
    private $terminatedWithEOF;

    /**
     * @var         String                                                                  `String`
     */
    private $stringified;

    /**
     * CommentToken constructor.
     * @param       String                                  $text                           `String`
     * The comment's text.
     *
     * @param       Bool                                    $terminatedWithEOF              `Bool`
     * Whether the comment is unterminated.
     */
    function __construct(String $text, Bool $terminatedWithEOF){
        $this->text = $text;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    /** @inheritDoc */
    function __toString(): String{
        if($this->stringified === NULL){
            $this->stringified = "/*" . $this->text;
            $this->stringified .= $this->terminatedWithEOF ? "" : "*/";
        }
        return $this->stringified;
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->text, $this->text) &&
            match($other->terminatedWithEOF, $this->terminatedWithEOF);
    }

    /**
     * Returns the comment's text.
     *
     * @returns     String                                                                  `String`
     * Returns the comment's text.
     */
    function getText(): String{
        return $this->text;
    }

    /**
     * Returns {@see TRUE} if the comment is unterminated.
     *
     * @returns     Bool                                                                    `Bool`
     * Returns {@see TRUE} if the comment is unterminated.
     */
    function isTerminatedWithEOF(): Bool{
        return $this->terminatedWithEOF;
    }
}
