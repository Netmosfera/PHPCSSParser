<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

function getValidEscapeStartersSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->selectAll();
    $set->remove(cp("\n"));
    $set->remove(cp("\f"));
    $set->remove(cp("\r"));
    return $set;
}
