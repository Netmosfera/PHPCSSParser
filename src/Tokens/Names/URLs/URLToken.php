<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function implode;
use function Netmosfera\PHPCSSAST\match;
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
     * @var         WhitespaceToken|NULL                                                    `WhitespaceToken|NULL`
     */
    private $whitespaceBefore;

    /**
     * @TODO
     */
    private $pieces;

    /**
     * @var         WhitespaceToken|NULL                                                    `WhitespaceToken|NULL`
     */
    private $whitespaceAfter;

    /**
     * @var         Bool                                                                    `Bool`
     */
    private $terminatedWithEOF;

    /**
     * @var         String|NULL                                                             `String|NULL`
     */
    private $stringified;

    function __construct(
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

    function getWhitespaceBefore(): ?WhitespaceToken{
        return $this->whitespaceBefore;
    }

    function getPieces(): Array{
        return $this->pieces;
    }

    function getWhitespaceAfter(): ?WhitespaceToken{
        return $this->whitespaceAfter;
    }

    function isTerminatedWithEOF(): Bool{
        return $this->terminatedWithEOF;
    }
}
