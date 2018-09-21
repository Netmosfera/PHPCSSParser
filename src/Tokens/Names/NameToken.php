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

    private $evaluated;

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

    function evaluate(): String{
        if($this->evaluated === NULL){
            $this->evaluated = "";
            foreach($this->pieces as $piece){
                if($piece instanceof ValidEscape){
                    $this->evaluated .= $piece->evaluate();
                }elseif(is_string($piece)){
                    $this->evaluated .= $piece;
                }else{
                    throw new Error();
                }
            }
        }
        return $this->evaluated;
    }
}
