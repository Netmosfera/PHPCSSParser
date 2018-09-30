<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSASTDev\Data\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

function getASCIINameStartersSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("_"));
    $set->addAll(getLettersSet());
    $set->addAll(new ContiguousCodePointsSet(cp("\u{80}"), cp("\u{FF}")));
    return $set;
}
