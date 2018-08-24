<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\SpecData\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getLettersSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->addAll(getLCLettersSet());
    $set->addAll(getUCLettersSet());
    return $set;
}
