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
    private $whitespaceBefore;

    /**
     * @var         URLBitToken[]|ValidEscapeToken[]
     * `Array<Int, URLBitToken|ValidEscapeToken>`
     */
    private $pieces;

    /**
     * @var         BadURLRemnantsToken
     * `BadURLRemnantsToken`
     */
    private $badURLRemnants;

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
     * @param       BadURLRemnantsToken                     $badURLRemnants
     * `BadURLRemnantsToken`
     * @TODOC
     */
    public function __construct(
        ?WhitespaceToken $whitespaceBefore,
        Array $pieces,
        BadURLRemnantsToken $badURLRemnants
    ){
        $this->whitespaceBefore = $whitespaceBefore;
        $this->pieces = $pieces;
        $this->badURLRemnants = $badURLRemnants;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->stringified === NULL){
            $this->stringified = "url(" .
                $this->whitespaceBefore .
                implode("", $this->pieces) .
                $this->badURLRemnants;
        }
        return $this->stringified;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->whitespaceBefore, $other->whitespaceBefore) &&
            match($this->pieces, $other->pieces) &&
            match($this->badURLRemnants, $other->badURLRemnants);
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
     * @return      BadURLRemnantsToken
     * `BadURLRemnantsToken`
     * @TODOC
     */
    public function getRemnants(): BadURLRemnantsToken{
        return $this->badURLRemnants;
    }
}
