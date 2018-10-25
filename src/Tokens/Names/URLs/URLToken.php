<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

use function implode;
use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

/**
 * A {@see URLToken} is `url(` followed by words and delimiters, and finally by `)`.
 *
 * A {@see URLToken} is a special token that allows URLs to appear unquoted. For example
 * the CSS code `url(path/image.gif)` represents a single {@see URLToken}, as opposed to
 * `url('path/image.gif')` which is is instead a sequence of three tokens:
 *
 * - {@see FunctionToken} `url(`
 * - {@see StringToken} `'path/image.gif'`
 * - {@see RightParenthesisToken} `)`
 *
 * A {@see URLToken} may also appear without the final `)`, when it is interrupted by
 * `EOF`; this is considered a parse error, however.
 */
class URLToken implements AnyURLToken
{
    /**
     * @var         IdentifierToken
     * `IdentifierToken`
     */
    private $_identifier;

    /**
     * @var         WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     */
    private $_whitespaceBefore;

    /**
     * @var         URLBitToken[]|ValidEscapeToken[]
     * `Array<Int, URLBitToken|ValidEscapeToken>`
     */
    private $_pieces;

    /**
     * @var         WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     */
    private $_whitespaceAfter;

    /**
     * @var         Bool
     * `Bool`
     */
    private $_EOFTerminated;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $_stringValue;

    /**
     * @var         Int
     * `Int`
     */
    private $_newlineCount;

    /**
     * @param       IdentifierToken $identifier
     * `IdentifierToken`
     * @TODOC
     *
     * @param       WhitespaceToken|NULL $whitespaceBefore
     * `WhitespaceToken|NULL`
     * @TODOC
     *
     * @param       URLBitToken[]|ValidEscapeToken[] $pieces
     * `Array<Int, URLBitToken|ValidEscapeToken>`
     * @TODOC
     *
     * @param       WhitespaceToken|NULL $whitespaceAfter
     * `WhitespaceToken|NULL`
     * @TODOC
     *
     * @param       Bool $EOFTerminated
     * `Bool`
     * @TODOC
     */
    public function __construct(
        IdentifierToken $identifier,
        ?WhitespaceToken $whitespaceBefore,
        array $pieces,
        ?WhitespaceToken $whitespaceAfter,
        Bool $EOFTerminated
    ){
        $this->_identifier = $identifier;
        $this->_whitespaceBefore = $whitespaceBefore;
        $this->_pieces = $pieces;
        $this->_whitespaceAfter = $whitespaceAfter;
        $this->_EOFTerminated = $EOFTerminated;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->_stringValue === NULL){
            $this->_stringValue =
                $this->_identifier . "(" .
                $this->_whitespaceBefore .
                implode("", $this->_pieces) .
                $this->_whitespaceAfter .
                ($this->_EOFTerminated ? "" : ")");
        }
        return $this->_stringValue;
    }

    /** @inheritDoc */
    public function isParseError(): Bool{
        return $this->_EOFTerminated;
    }

    /** @inheritDoc */
    public function newlineCount(): Int{
        if($this->_newlineCount === NULL){
            $count = 0;
            foreach($this->_pieces as $piece){
                $count += $piece->newlineCount();
            }
            $this->_newlineCount = $count;
        }
        return $this->_newlineCount;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_identifier, $other->_identifier) &&
            match($this->_whitespaceBefore, $other->_whitespaceBefore) &&
            match($this->_pieces, $other->_pieces) &&
            match($this->_whitespaceAfter, $other->_whitespaceAfter) &&
            match($this->_EOFTerminated, $other->_EOFTerminated);
    }

    /**
     * @TODOC
     *
     * @return      IdentifierToken
     * `IdentifierToken`
     * @TODOC
     */
    public function identifier(): IdentifierToken{
        return $this->_identifier;
    }

    /**
     * @TODOC
     *
     * @return      WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     * @TODOC
     */
    public function whitespaceBefore(): ?WhitespaceToken{
        return $this->_whitespaceBefore;
    }

    /**
     * @TODOC
     *
     * @return      URLBitToken[]|ValidEscapeToken[]
     * `Array<Int, URLBitToken|ValidEscapeToken>`
     * @TODOC
     */
    public function pieces(): array{
        return $this->_pieces;
    }

    /**
     * @TODOC
     *
     * @return      WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     * @TODOC
     */
    public function whitespaceAfter(): ?WhitespaceToken{
        return $this->_whitespaceAfter;
    }

    /**
     * @TODOC
     *
     * @return      Bool
     * `Bool`
     * @TODOC
     */
    public function EOFTerminated(): Bool{
        return $this->_EOFTerminated;
    }
}
