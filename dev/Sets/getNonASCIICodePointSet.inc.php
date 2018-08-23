<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Sets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use Netmosfera\PHPCSSASTDev\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSASTDev\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getNonASCIICodePointSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->addAll(new ContiguousCodePointsSet(cp("\u{80}"), cp("\u{10FFFF}")));
    return $set;
}
