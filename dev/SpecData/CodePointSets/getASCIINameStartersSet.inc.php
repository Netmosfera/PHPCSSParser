<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\SpecData\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getASCIINameStartersSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("_"));
    $set->addAll(getLettersSet());
    return $set;
}
