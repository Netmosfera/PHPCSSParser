<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Examples;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getDigitSeqsSet(){
    $digits = ["0", "5", "9"];
    foreach($digits as $digit){
        yield $digit;
        yield "1" . $digit;
        yield "21" . $digit;
        yield "321" . $digit;
        yield "4321" . $digit;
        yield "54321" . $digit;
    }
    yield "000000";
    yield "111111";
    yield "555555";
    yield "999999";
}


