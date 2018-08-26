<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class BadURLToken
{
    public $whitespaceBefore;

    public $pieces;

    public $badURLRemnants;

    function __construct(
        ?WhitespaceToken $whitespaceBefore,
        Array $pieces,
        $badURLRemnants  // @TODO add type
    ){
        $this->whitespaceBefore = $whitespaceBefore;
        $this->pieces = $pieces;
        $this->badURLRemnants = $badURLRemnants;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->whitespaceBefore, $other->whitespaceBefore) &&
            match($this->pieces, $other->pieces) &&
            match($this->badURLRemnants, $other->badURLRemnants);
    }
}
