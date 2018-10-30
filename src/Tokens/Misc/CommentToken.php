<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\RootToken;
use function Netmosfera\PHPCSSAST\match;

/**
 * A {@see CommentToken} is a programmer-readable explanation or annotation.
 *
 * It can contain any text, except the character `*` followed by `/`.
 */
class CommentToken implements RootToken
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
    private $_EOFTerminated;

    /**
     * @param       String $text
     * `String`
     * The comment's text.
     *
     * @param       Bool $EOFTerminated
     * `Bool`
     * Whether the comment is unterminated.
     */
    public function __construct(String $text, Bool $EOFTerminated){
        $this->_text = $text;
        $this->_EOFTerminated = $EOFTerminated;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return "/*" . $this->_text . ($this->_EOFTerminated ? "" : "*/");
    }

    /** @inheritDoc */
    public function isParseError(): Bool{
        return $this->_EOFTerminated;
    }

    /** @inheritDoc */
    public function newlineCount(): Int{ // @memo
        return preg_match_all(
            "/(" . SpecData::NEWLINES_REGEX_SEQS. ")/usD",
            $this->_text
        );
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_text, $this->_text) &&
            match($other->_EOFTerminated, $this->_EOFTerminated);
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
    public function EOFTerminated(): Bool{
        return $this->_EOFTerminated;
    }
}
