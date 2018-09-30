<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;

/**
 * An {@see NameToken} is a "word" in the CSS language.
 *
 * It is any sequence of readable characters that is not a number, an escape
 * sequence, an operator or a generic delimiter.
 */
class NameToken implements EvaluableToken
{
    /**
     * @var         NameBitToken[]|ValidEscapeToken[]
     * `Array<Int, NameBitToken|ValidEscapeToken>`
     */
    private $pieces;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $value;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $stringified;

    /**
     * @param       NameBitToken[]|ValidEscapeToken[]       $pieces
     * `Array<Int, NameBitToken|ValidEscapeToken>`
     * The {@see NameToken}'s components.
     */
    public function __construct(Array $pieces){
        $this->pieces = $pieces;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->stringified === NULL){
            $this->stringified = implode("", $this->pieces);
        }
        return $this->stringified;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->pieces, $other->pieces);
    }

    /** @inheritDoc */
    public function getValue(): String{
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
     * @return      NameBitToken[]|ValidEscapeToken[]
     * `Array<Int, NameBitToken|ValidEscapeToken>`
     * Returns the {@see NameToken}'s components.
     */
    public function getPieces(): Array{
        return $this->pieces;
    }
}
