<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSAST\Tokens\ColonToken;
use Netmosfera\PHPCSSAST\Tokens\CommaToken;
use Netmosfera\PHPCSSAST\Tokens\SemicolonToken;
use Netmosfera\PHPCSSAST\Tokens\LeftParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\RightParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\LeftCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\LeftSquareBracketToken;
use Netmosfera\PHPCSSAST\Tokens\RightCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\RightSquareBracketToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatToken(Traverser $t){
    $codePoint = $t->eatExp(".");
    if(has($codePoint)){
        if($codePoint === ":"){ return new ColonToken(); }
        if($codePoint === ","){ return new CommaToken(); }
        if($codePoint === "{"){ return new LeftCurlyBracketToken(); }
        if($codePoint === "("){ return new LeftParenthesisToken(); }
        if($codePoint === "["){ return new LeftSquareBracketToken(); }
        if($codePoint === "}"){ return new RightCurlyBracketToken(); }
        if($codePoint === ")"){ return new RightParenthesisToken(); }
        if($codePoint === "]"){ return new RightSquareBracketToken(); }
        if($codePoint === ";"){ return new SemicolonToken(); }
    }
}
