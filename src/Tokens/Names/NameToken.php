<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscape;
use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * An {@see NameToken} is a "word" in the CSS language.
 *
 * It is any sequence of readable characters that is not a number, an escape sequence, an
 * operator or a generic delimiter.
 */
class NameToken implements EvaluableToken
{
    /**
     * @var         NameBitToken[]|ValidEscape[]                                            `Array<Int, NameBitToken|ValidEscape>`
     */
    private $pieces;

    /**
     * @var         String|NULL                                                             `String|NULL`
     */
    private $value;

    /**
     * @var         String|NULL                                                             `String|NULL`
     */
    private $stringified;

    /**
     * @param       NameBitToken[]|ValidEscape[]            $pieces                         `Array<Int, NameBitToken|ValidEscape>`
     * The {@see NameToken}'s components.
     */
    function __construct(Array $pieces){
        foreach($pieces as $piece){ // @TODO move this assertion to CheckedNameToken
            assert($piece instanceof NameBitToken || $piece instanceof ValidEscape);
        }
        $this->pieces = $pieces;
    }

    /** @inheritDoc */
    function __toString(): String{
        if($this->stringified === NULL){
            $this->stringified = implode("", $this->pieces);
        }
        return $this->stringified;
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->pieces, $other->pieces);
    }

    /** @inheritDoc */
    function getValue(): String{
        if($this->value === NULL){
            $this->value = "";
            foreach($this->pieces as $piece){
                $this->value .= $piece->getValue();
            }
        }
        return $this->value;
    }

    /**
     * Returns the {@see NameToken}'s components.
     *
     * @returns     NameBitToken[]|ValidEscape[]                                            `Array<Int, NameBitToken|ValidEscape>`
     * Returns the {@see NameToken}'s components.
     */
    function getPieces(): Array{
        return $this->pieces;
    }
}
