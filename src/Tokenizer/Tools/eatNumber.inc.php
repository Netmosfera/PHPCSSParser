<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\has;
use function Netmosfera\PHPCSSAST\Tokenizer\hasNo;
use function Netmosfera\PHPCSSAST\Tokenizer\mayHave;
use Netmosfera\PHPCSSAST\Tokens\_Number;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatNumber(Traverser $t): _Number{
    assert(isNumberStart($t));

    $sign = $t->eatExp("\+|-");

    $wholes = $t->eatExp("[0-9]+");

    $dt = $t->createBranch();
    if(
        has($dt->eatStr(".")) &&
        has($decimals = $dt->eatExp('[0-9]+'))
    ){
        $t->importBranch($dt);
    }else{
        $decimals = NULL;
    }

    $et = $t->createBranch();
    if(
        has($expLetter = $et->eatExp("e|E")) &&
        mayHave($expSign = $et->eatExp("\+|-")) &&
        has($exponent = $dt->eatStr("[0-9]+"))
    ){
        $t->importBranch($et);
    }else{
        $expLetter = $expSign = $exponent = NULL;
    }

    return new _Number(
        $sign,
        $wholes,
        $decimals,
        $expLetter,
        $expSign,
        $exponent
    );
}
