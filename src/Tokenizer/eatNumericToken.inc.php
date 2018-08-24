<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\Tools\isIdentifierStart;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\isNumberStart;
use Netmosfera\PHPCSSAST\Tokens\DimensionToken;
use Netmosfera\PHPCSSAST\Tokens\PercentageToken;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatNumericToken(Traverser $t){
    assert(isNumberStart($t));

    $number = eatNumberToken($t);

    if(has($t->eatStr("%"))){
        return new PercentageToken($number);
    }elseif(isIdentifierStart($t)){
        $name = eatIdentifierToken($t);
        return new DimensionToken($number, $name);
    }

    return $number;
}
