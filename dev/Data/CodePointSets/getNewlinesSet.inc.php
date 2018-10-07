<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

function getNewlinesSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->add(cp("\n"));
    $set->add(cp("\r"));
    $set->add(cp("\f"));
    return $set;
}
