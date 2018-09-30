<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

function getNameItemsSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("-"));
    $set->addAll(getNameStartersSet());
    $set->addAll(getDigitsSet());
    return $set;
}
