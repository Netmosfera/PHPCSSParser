<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

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
     * `Array<Int, URLBitToken|ValidEscape>`
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
     * `Array<Int, URLBitToken|ValidEscape>`
     * @TODOC
     *
     * @param       BadURLRemnantsToken                     $badURLRemnants
     * `BadURLRemnantsToken`
     * @TODOC
     */
    function __construct(
        ?WhitespaceToken $whitespaceBefore,
        Array $pieces,
        BadURLRemnantsToken $badURLRemnants
    ){
        foreach($pieces as $piece){
            assert($piece instanceof URLBitToken || $piece instanceof ValidEscapeToken);
        }

        $this->whitespaceBefore = $whitespaceBefore;
        $this->pieces = $pieces;
        $this->badURLRemnants = $badURLRemnants;
    }

    /** @inheritDoc */
    function __toString(): String{
        if($this->stringified === NULL){
            $this->stringified = "url(" .
                $this->whitespaceBefore .
                implode("", $this->pieces) .
                $this->badURLRemnants;
        }
        return $this->stringified;
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->whitespaceBefore, $other->whitespaceBefore) &&
            match($this->pieces, $other->pieces) &&
            match($this->badURLRemnants, $other->badURLRemnants);
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
     * @returns     BadURLRemnantsToken
     * `BadURLRemnantsToken`
     * @TODOC
     */
    function getRemnants(): BadURLRemnantsToken{
        return $this->badURLRemnants;
    }
}
