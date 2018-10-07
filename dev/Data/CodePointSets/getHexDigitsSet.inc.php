<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSASTDev\Data\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

function getHexDigitsSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->addAll(new ContiguousCodePointsSet(cp("0"), cp("9")));
    $set->addAll(new ContiguousCodePointsSet(cp("a"), cp("f")));
    $set->addAll(new ContiguousCodePointsSet(cp("A"), cp("F")));
    return $set;
}
