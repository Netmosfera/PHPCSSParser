<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getEncodedCodePointEscapeSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->selectAll();
    $set->removeAll(getNewlinesSet());
    $set->removeAll(getHexDigitsSet());
    return $set;
}
