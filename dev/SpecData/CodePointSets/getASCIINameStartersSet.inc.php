<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\SpecData\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use Netmosfera\PHPCSSASTDev\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSASTDev\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getASCIINameStartersSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("_"));
    $set->addAll(getLettersSet());
    $set->addAll(new ContiguousCodePointsSet(cp("\u{80}"), cp("\u{FF}")));
    return $set;
}
