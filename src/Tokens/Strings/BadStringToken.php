<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\Escape;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Bad String Token.
 *
 * A bad string is a string terminated with a newline, which is not allowed in a string
 * unless it is escaped.
 *
 * Strings terminated with EOF are not considered "bad", but they are parse errors.
 */
class BadStringToken implements AnyStringToken
{
    private $delimiter;

    private $pieces;

    function __construct(String $delimiter, Array $pieces){
        foreach($pieces as $piece){ // @TODO move to CheckedStringToken
            assert($piece instanceof StringBitToken || $piece instanceof Escape);
        }
        $this->delimiter = $delimiter;
        $this->pieces = $pieces;
    }

    function __toString(): String{
        return $this->delimiter . implode("", $this->pieces);
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->delimiter, $other->delimiter) &&
            match($this->pieces, $other->pieces);
    }

    function getDelimiter(): String{
        return $this->delimiter;
    }

    function getPieces(): array{
        return $this->pieces;
    }
}
