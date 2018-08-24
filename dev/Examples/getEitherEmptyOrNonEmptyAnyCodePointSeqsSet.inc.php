<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Examples;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(){
    return [
        "",
        "skip \u{2764} me"
    ];
}
