<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSASTDev\Data\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getNonASCIIsSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->addAll(new ContiguousCodePointsSet(cp("\u{80}"), cp("\u{10FFFF}")));
    $set->add(cp("\0")); // the null byte is converted to the replacement character, which is contained in the range above
    return $set;
}
