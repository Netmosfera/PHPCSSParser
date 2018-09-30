<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;

function getURLTokenBitDisallowedSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->selectAll();
    $set->removeAll(getURLTokenBitSet());
    return $set;
}
