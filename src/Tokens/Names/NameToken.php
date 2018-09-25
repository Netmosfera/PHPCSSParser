<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscape;
use Error;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class NameToken implements IdentifierLikeToken
{
    private $pieces;

    private $value;

    function __construct(Array $pieces){
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

    function getValue(): String{
        if($this->value === NULL){
            $this->value = "";
            foreach($this->pieces as $piece){
                if($piece instanceof ValidEscape){
                    $this->value .= $piece->getValue();
                }elseif(is_string($piece)){
                    $this->value .= $piece;
                }else{
                    throw new Error();
                }
            }
        }
        return $this->value;
    }
}
