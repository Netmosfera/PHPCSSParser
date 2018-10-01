<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

use function implode;
use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

/**
 * A {@see URLToken} is `url(` followed by words and delimiters, and finally by `)`.
 *
 * The code `url(path/image.gif)` represents a single {@see URLToken}, unlike
 * `url('path/image.gif')` that is instead a sequence of three tokens:
 *
 * - {@see FunctionToken} `url(`
 * - {@see StringToken} `'path/image.gif'`
 * - {@see RightParenthesisToken} `)`
 */
class URLToken implements AnyURLToken
{
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
    private $_precedesEOF;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $_stringValue;

    /**
     * @param       WhitespaceToken|NULL                    $whitespaceBefore
     * `WhitespaceToken|NULL`
     * @TODOC
     *
     * @param       URLBitToken[]|ValidEscapeToken[]        $pieces
     * `Array<Int, URLBitToken|ValidEscapeToken>`
     * @TODOC
     *
     * @param       WhitespaceToken|NULL                    $whitespaceAfter
     * `WhitespaceToken|NULL`
     * @TODOC
     *
     * @param       Bool                                    $precedesEOF
     * `Bool`
     * @TODOC
     */
    public function __construct(
        ?WhitespaceToken $whitespaceBefore,
        Array $pieces,
        ?WhitespaceToken $whitespaceAfter,
        Bool $precedesEOF
    ){
        $this->_whitespaceBefore = $whitespaceBefore;
        $this->_pieces = $pieces;
        $this->_whitespaceAfter = $whitespaceAfter;
        $this->_precedesEOF = $precedesEOF;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->_stringValue === NULL){
            $this->_stringValue = "url(" .
                $this->_whitespaceBefore .
                implode("", $this->_pieces) .
                $this->_whitespaceAfter .
                ($this->_precedesEOF ? "" : ")");
        }
        return $this->_stringValue;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_whitespaceBefore, $other->_whitespaceBefore) &&
            match($this->_pieces, $other->_pieces) &&
            match($this->_whitespaceAfter, $other->_whitespaceAfter) &&
            match($this->_precedesEOF, $other->_precedesEOF);
    }

    /**
     * @TODOC
     *
     * @return      WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     * @TODOC
     */
    public function getWhitespaceBefore(): ?WhitespaceToken{
        return $this->_whitespaceBefore;
    }

    /**
     * @TODOC
     *
     * @return      URLBitToken[]|ValidEscapeToken[]
     * `Array<Int, URLBitToken|ValidEscapeToken>`
     * @TODOC
     */
    public function getPieces(): Array{
        return $this->_pieces;
    }

    /**
     * @TODOC
     *
     * @return      WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     * @TODOC
     */
    public function getWhitespaceAfter(): ?WhitespaceToken{
        return $this->_whitespaceAfter;
    }

    /**
     * @TODOC
     *
     * @return      Bool
     * `Bool`
     * @TODOC
     */
    public function precedesEOF(): Bool{
        return $this->_precedesEOF;
    }
}
