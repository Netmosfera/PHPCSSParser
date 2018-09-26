<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscape;
use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class NameToken implements EvaluableToken
{
    /** @var NameBitToken[]|ValidEscape[] */
    private $pieces;

    private $value;

    function __construct(Array $pieces){
        foreach($pieces as $piece){ // @TODO move this assertion to CheckedNameToken
            assert($piece instanceof NameBitToken || $piece instanceof ValidEscape);
        }
        $this->pieces = $pieces;
    }

    function __toString(): String{
        return implode("", $this->pieces);
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->pieces, $other->pieces);
    }

    function getPieces(): array{
        return $this->pieces;
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
}
