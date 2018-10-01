<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

/**
 * A {@see BadURLToken} is a {@see URLToken} that terminates with invalid data.
 */
class BadURLToken implements AnyURLToken
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
     * @var         BadURLRemnantsToken
     * `BadURLRemnantsToken`
     */
    private $_badURLRemnants;

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
     * @param       BadURLRemnantsToken                     $badURLRemnants
     * `BadURLRemnantsToken`
     * @TODOC
     */
    public function __construct(
        ?WhitespaceToken $whitespaceBefore,
        Array $pieces,
        BadURLRemnantsToken $badURLRemnants
    ){
        $this->_whitespaceBefore = $whitespaceBefore;
        $this->_pieces = $pieces;
        $this->_badURLRemnants = $badURLRemnants;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->_stringValue === NULL){
            $this->_stringValue = "url(" .
                $this->_whitespaceBefore .
                implode("", $this->_pieces) .
                $this->_badURLRemnants;
        }
        return $this->_stringValue;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_whitespaceBefore, $other->_whitespaceBefore) &&
            match($this->_pieces, $other->_pieces) &&
            match($this->_badURLRemnants, $other->_badURLRemnants);
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
    public function pieces(): Array{
        return $this->_pieces;
    }

    /**
     * @TODOC
     *
     * @return      BadURLRemnantsToken
     * `BadURLRemnantsToken`
     * @TODOC
     */
    public function remnants(): BadURLRemnantsToken{
        return $this->_badURLRemnants;
    }
}
