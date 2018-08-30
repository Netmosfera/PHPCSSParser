<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSAST\Tokens\IdentifierToken;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\Escapes\isBSValidEscape;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\Escapes\eatAnyEscape;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNameCodePointSeq;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\isIdentifierStart;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatIdentifierToken(Traverser $t){
    assert(isIdentifierStart($t));

    $pieces = [];

    EAT_PIECE:

    if(isBSValidEscape($t)){
        $t->eatStr("\\");
        $pieces[] = eatAnyEscape($t);
    }elseif(has($plainCPs = eatNameCodePointSeq($t))){
        $pieces[] = $plainCPs;
    }else{
        return new IdentifierToken($pieces);
    }

    goto EAT_PIECE;
}
