<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Token;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CommentToken implements Token
{
    private $text;

    private $terminatedWithEOF;

    function __construct(
        String $text,
        Bool $terminatedWithEOF
    ){
        $this->text = $text;
        $this->terminatedWithEOF = $terminatedWithEOF;
    }

    function __toString(): String{
        return "/*" . $this->text . ($this->terminatedWithEOF ? "" : "*/");
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->text, $this->text) &&
            match($other->terminatedWithEOF, $this->terminatedWithEOF);
    }

    function getText(): String{
        return $this->text;
    }

    function isTerminatedWithEOF(): Bool{
        return $this->terminatedWithEOF;
    }
}
