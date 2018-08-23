<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class Comment
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
            $other->text === $this->text &&
            $other->terminated === $this->terminated;
    }
}
