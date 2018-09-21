<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Operators;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Token;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 *
 * N.B. That a delimiter token of value `\` represents a parse-error.
 */
class DelimiterToken implements Token
{
    private $delimiter;

    function __construct(String $delimiter){
        $this->delimiter = $delimiter;
    }

    function __toString(): String{
        return $this->delimiter;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->delimiter, $this->delimiter);
    }
}
