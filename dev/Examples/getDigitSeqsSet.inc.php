<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Examples;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getDigitSeqsSet(){
    $digits = [0, 5, 9];
    foreach($digits as $d0){
        yield $d0;
        foreach($digits as $d1){
            yield $d0 . $d1;
            foreach($digits as $d2){
                yield $d0 . $d1 . $d2;
                foreach($digits as $d3){
                    yield $d0 . $d1 . $d2 . $d3;
                }
            }
        }
    }
}
