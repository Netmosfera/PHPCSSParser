<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSASTDev\Data\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

function getNameStartersSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("_"));
    $set->addAll(new ContiguousCodePointsSet(cp("a"), cp("z")));
    $set->addAll(new ContiguousCodePointsSet(cp("A"), cp("Z")));
    $set->addAll(getNonASCIIsSet());
    return $set;
}
