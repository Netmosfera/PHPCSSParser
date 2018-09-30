<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

use function implode;
use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

/**
 * A {@see URLToken} is `url(` followed by words and delimiters, and finally
 * followed by `)`.
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
    private $whitespaceBefore;

    /**
     * @var         URLBitToken[]|ValidEscapeToken[]
     * `Array<Int, URLBitToken|ValidEscapeToken>`
     */
    private $pieces;

    /**
     * @var         WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     */
    private $whitespaceAfter;

    /**
     * @var         Bool
     * `Bool`
     */
    private $terminatedWithEOF;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $stringified;

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
     * @param       Bool                                    $terminatedWithEOF
     * `Bool`
     * @TODOC
     */
    public function __construct(
        ?WhitespaceToken $whitespaceBefore,
        Array $pieces,
        ?WhitespaceToken $whitespaceAfter,
        Bool $terminatedWithEOF
    ){
        $this->whitespaceBefore = $whitespaceBefore;
        $this->pieces = $pieces;
        $this->whitespaceAfter = $whitespaceAfter;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->stringified === NULL){
            $this->stringified = "url(" .
                $this->whitespaceBefore .
                implode("", $this->pieces) .
                $this->whitespaceAfter .
                ($this->terminatedWithEOF ? "" : ")");
        }
        return $this->stringified;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->whitespaceBefore, $other->whitespaceBefore) &&
            match($this->pieces, $other->pieces) &&
            match($this->whitespaceAfter, $other->whitespaceAfter) &&
            match($this->terminatedWithEOF, $other->terminatedWithEOF);
    }

    /**
     * @TODOC
     *
     * @return      WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     * @TODOC
     */
    public function getWhitespaceBefore(): ?WhitespaceToken{
        return $this->whitespaceBefore;
    }

    /**
     * @TODOC
     *
     * @return      URLBitToken[]|ValidEscapeToken[]
     * `Array<Int, URLBitToken|ValidEscapeToken>`
     * @TODOC
     */
    public function getPieces(): Array{
        return $this->pieces;
    }

    /**
     * @TODOC
     *
     * @return      WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     * @TODOC
     */
    public function getWhitespaceAfter(): ?WhitespaceToken{
        return $this->whitespaceAfter;
    }

    /**
     * @TODOC
     *
     * @return      Bool
     * `Bool`
     * @TODOC
     */
    public function isTerminatedWithEOF(): Bool{
        return $this->terminatedWithEOF;
    }
}
