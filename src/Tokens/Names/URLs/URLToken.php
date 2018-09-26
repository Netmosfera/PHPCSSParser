<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function implode;
use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

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
    private $whitespaceBefore;

    /**
     * @var         URLBitToken[]|ValidEscapeToken[]
     * `Array<Int, URLBitToken|ValidEscape>`
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
     * `Array<Int, URLBitToken|ValidEscape>`
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
    function __construct(
        ?WhitespaceToken $whitespaceBefore,
        Array $pieces,
        ?WhitespaceToken $whitespaceAfter,
        Bool $terminatedWithEOF
    ){
        foreach($pieces as $piece){
            assert($piece instanceof URLBitToken || $piece instanceof ValidEscapeToken);
        }
        $this->whitespaceBefore = $whitespaceBefore;
        $this->pieces = $pieces;
        $this->whitespaceAfter = $whitespaceAfter;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    /** @inheritDoc */
    function __toString(): String{
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
    function equals($other): Bool{
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
     * @returns     WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     * @TODOC
     */
    function getWhitespaceBefore(): ?WhitespaceToken{
        return $this->whitespaceBefore;
    }

    /**
     * @TODOC
     *
     * @returns     URLBitToken[]|ValidEscapeToken[]
     * `Array<Int, URLBitToken|ValidEscape>`
     * @TODOC
     */
    function getPieces(): Array{
        return $this->pieces;
    }

    /**
     * @TODOC
     *
     * @returns     WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     * @TODOC
     */
    function getWhitespaceAfter(): ?WhitespaceToken{
        return $this->whitespaceAfter;
    }

    /**
     * @TODOC
     *
     * @returns     Bool
     * `Bool`
     * @TODOC
     */
    function isTerminatedWithEOF(): Bool{
        return $this->terminatedWithEOF;
    }
}
