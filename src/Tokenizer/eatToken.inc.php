<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Colon;
use Netmosfera\PHPCSSAST\Tokens\Comma;
use Netmosfera\PHPCSSAST\Tokens\Semicolon;
use Netmosfera\PHPCSSAST\Tokens\LeftParenthesis;
use Netmosfera\PHPCSSAST\Tokens\RightParenthesis;
use Netmosfera\PHPCSSAST\Tokens\LeftCurlyBracket;
use Netmosfera\PHPCSSAST\Tokens\LeftSquareBracket;
use Netmosfera\PHPCSSAST\Tokens\RightCurlyBracket;
use Netmosfera\PHPCSSAST\Tokens\RightSquareBracket;
use function Netmosfera\PHPCSSAST\Tokenizer\has;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatToken(Traverser $t){
    $codePoint = $t->eatExp(".");
    if(has($codePoint)){
        if($codePoint === ":"){ return new Colon(); }
        if($codePoint === ","){ return new Comma(); }
        if($codePoint === "{"){ return new LeftCurlyBracket(); }
        if($codePoint === "("){ return new LeftParenthesis(); }
        if($codePoint === "["){ return new LeftSquareBracket(); }
        if($codePoint === "}"){ return new RightCurlyBracket(); }
        if($codePoint === ")"){ return new RightParenthesis(); }
        if($codePoint === "]"){ return new RightSquareBracket(); }
        if($codePoint === ";"){ return new Semicolon(); }
    }
}
