<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getURLTokenBitDisallowedSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->selectAll();
    $set->removeAll(getURLTokenBitSet());
    return $set;
}
