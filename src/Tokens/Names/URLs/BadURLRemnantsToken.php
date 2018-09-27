<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Token;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 */
class BadURLRemnantsToken implements Token
{
    /**
     * @var         BadURLRemnantsBitToken[]|EscapeToken[]
     * @TODOC
     */
    private $pieces;

    /**
     * @var         Bool
     * `Bool`
     * @TODOC
     */
    private $terminatedWithEOF;

    /**
     * @param       BadURLRemnantsBitToken[]|EscapeToken[]  $pieces
     * `Array<Int, BadURLRemnantsBitToken|EscapeToken>`
     * @TODOC
     *
     * @param       Bool                                    $terminatedWithEOF
     * `Bool`
     * @TODOC
     */
    function __construct(Array $pieces, Bool $terminatedWithEOF){
        foreach($pieces as $piece){
            assert($piece instanceof BadURLRemnantsBitToken || $piece instanceof EscapeToken);
        }
        $this->pieces = $pieces;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    /** @inheritDoc */
    function __toString(): String{
        return
            implode("", $this->pieces) .
            ($this->terminatedWithEOF ? "" : ")");
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->pieces, $other->pieces) &&
            match($this->terminatedWithEOF, $other->terminatedWithEOF);
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
