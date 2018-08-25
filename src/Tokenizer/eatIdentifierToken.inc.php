<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\IdentifierToken;
use Netmosfera\PHPCSSAST\Traverser;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNameCodePointSeq;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\isIdentifierStart;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\isValidEscape;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatEscape;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatIdentifierToken(Traverser $t){
    assert(isIdentifierStart($t));

    $pieces = [];

    EAT_PIECE:

    if(isValidEscape($t)){
        $t->eatStr("\\");
        $pieces[] = eatEscape($t);
    }elseif(has($plainCPs = eatNameCodePointSeq($t))){
        $pieces[] = $plainCPs;
    }else{
        return new IdentifierToken($pieces);
    }

    goto EAT_PIECE;
}