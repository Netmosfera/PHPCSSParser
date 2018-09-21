<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\SpecData\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getASCIINameItemsSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("-"));
    $set->addAll(getASCIINameStartersSet());
    $set->addAll(getDigitsSet());
    return $set;
}
