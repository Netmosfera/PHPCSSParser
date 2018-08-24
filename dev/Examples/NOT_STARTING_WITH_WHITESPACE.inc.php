<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Examples;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function NOT_STARTING_WITH_WHITESPACE(){
    $sequences[] = "sample \u{2764} string";
    $sequences[] = "";
    return $sequences;
}
