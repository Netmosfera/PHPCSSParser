<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;

class CommentToken
{
    public $text;
    public $terminated;

    function __construct(String $text, Bool $terminated = TRUE){
        $this->text = $text;
        $this->terminated = $terminated;
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->text, $this->text) &&
            match($other->terminated, $this->terminated);
    }
}
