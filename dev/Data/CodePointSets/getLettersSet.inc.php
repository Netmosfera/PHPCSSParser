<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;

function getLettersSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->addAll(getLCLettersSet());
    $set->addAll(getUCLettersSet());
    return $set;
}
