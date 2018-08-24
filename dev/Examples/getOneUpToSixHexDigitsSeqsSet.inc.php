<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Examples;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getOneUpToSixHexDigitsSeqsSet(){
    $digits = ["a", "f", "A", "F", "0", "9"];
    foreach($digits as $digit){
        yield $digit;
        yield "a" . $digit;
        yield "af" . $digit;
        yield "afA" . $digit;
        yield "afAF" . $digit;
        yield "afAF0" . $digit;
    }
    yield "000000";
    yield "999999";
    yield "aaaaaa";
    yield "ffffff";
    yield "AAAAAA";
    yield "FFFFFF";
}
