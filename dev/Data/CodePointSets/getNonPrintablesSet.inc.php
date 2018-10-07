<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSASTDev\Data\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

function getNonPrintablesSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->addAll(new ContiguousCodePointsSet(cp("\u{0}"), cp("\u{8}")));
    $set->add(cp("\u{B}"));
    $set->addAll(new ContiguousCodePointsSet(cp("\u{E}"), cp("\u{1F}")));
    $set->add(cp("\u{7F}"));
    return $set;
}
