<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\BadURLRemnants;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class BadURLToken
{
    public $whitespaceBefore;

    public $pieces;

    public $badURLRemnants;

    function __construct(
        String $whitespaceBefore,
        Array $pieces,
        BadURLRemnants $badURLRemnants
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
