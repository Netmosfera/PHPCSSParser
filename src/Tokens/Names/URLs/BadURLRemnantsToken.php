<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Token;

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
    public function __construct(Array $pieces, Bool $terminatedWithEOF){
        $this->pieces = $pieces;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return
            implode("", $this->pieces) .
            ($this->terminatedWithEOF ? "" : ")");
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->pieces, $other->pieces) &&
            match($this->terminatedWithEOF, $other->terminatedWithEOF);
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
