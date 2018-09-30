<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

function getNameStartersSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("_"));
    $set->addAll(getLettersSet());
    $set->addAll(getNonASCIIsSet());
    return $set;
}
