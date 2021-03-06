<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

function getWhitespacesSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->add(cp(" "));
    $set->add(cp("\t"));
    $set->addAll(getNewlinesSet());
    return $set;
}
