<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatEscape;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNewline;
use Netmosfera\PHPCSSAST\Tokens\BadStringToken;
use Netmosfera\PHPCSSAST\Tokens\StringToken;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/** @return StringToken|BadStringToken|NULL */
function eatStringToken(Traverser $t){
    $delimiter = $t->eatExp('\'|"');

    if(hasNo($delimiter)){ return NULL; }

    $pieces = [];

    EAT_PIECE:

    if($t->isEOF()){
        return new StringToken($delimiter, $pieces, TRUE);
    }elseif($t->eatStr($delimiter) === $delimiter){
        return new StringToken($delimiter, $pieces);
    }elseif(has(eatNewline($t->createBranch()))){
        return new BadStringToken($delimiter, $pieces);
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
