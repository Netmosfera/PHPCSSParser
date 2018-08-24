<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Examples;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getAnyCodePointSeqsSet(){
    $set = new CompressedCodePointSet();
    $set->selectAll();
    $sequences = getCodePointsFromRanges($set);
    $sequences[] = " \t \n \r \r\n \f ";
    $sequences[] = "skip \u{2764} me";
    $sequences[] = "";
    return $sequences;
}