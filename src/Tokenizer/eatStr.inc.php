<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\has;
use function Netmosfera\PHPCSSAST\Tokenizer\hasNo;
use Netmosfera\PHPCSSAST\Tokens\BadStr;
use Netmosfera\PHPCSSAST\Tokens\Str;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatStr(Traverser $t){
    $delimiter = $t->eatExp("'|\"");

    if(hasNo($delimiter)){ return NULL; }

    $pieces = [];

    EAT_PIECE:

    if($t->isEOF()){
        return new Str($pieces, TRUE);
    }elseif($t->eatStr($delimiter) === $delimiter){
        return new Str($pieces);
    }elseif(has(eatNewline($t->createBranch()))){
        return new BadStr($pieces);
    }elseif(has($t->eatStr("\\"))){
        $pieces[] = eatEscape($t);
    }else{
        $stringPiece = $t->eatExp('[^' . $t->escapeRegexp("\r\n\f\\" . $delimiter) . ']+');
        if(has($stringPiece)){
            $pieces[] = $stringPiece;
        }
    }

    goto EAT_PIECE;
}
