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
    private $_pieces;

    /**
     * @var         Bool
     * `Bool`
     * @TODOC
     */
    private $_precedesEOF;

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
        $this->_pieces = $pieces;
        $this->_precedesEOF = $terminatedWithEOF;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return
            implode("", $this->_pieces) .
            ($this->_precedesEOF ? "" : ")");
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_pieces, $other->_pieces) &&
            match($this->_precedesEOF, $other->_precedesEOF);
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
