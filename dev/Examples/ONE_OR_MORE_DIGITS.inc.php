<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Examples;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function ONE_OR_MORE_DIGITS(){
    $digits = ["0", "5", "9"];
    foreach($digits as $digit){
        yield $digit;
        yield "1" . $digit;
        yield "21" . $digit;
        yield "321" . $digit;
        yield "4321" . $digit;
    }
    yield "0000000000";
    yield "111111111";
    yield "55555555";
    yield "999999";
}


